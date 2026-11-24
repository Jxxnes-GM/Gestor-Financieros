<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cuenta;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransaccionController extends Controller
{

    public function index()
    {
        $transacciones = Transaccion::with(['cuenta', 'categoria'])
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10);

        return view('transacciones.index', compact('transacciones'));
    }

    public function create()
    {
        return view('transacciones.create', [
            'transaccion' => new Transaccion(['fecha' => now()->toDateString()]),
            'cuentas' => Cuenta::orderBy('nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }


    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['creado_por'] = $request->user()->name;   // usuario que registra

        DB::transaction(function () use ($datos) {
            $cuenta = Cuenta::lockForUpdate()->findOrFail($datos['cuenta_id']);

            // Un gasto no puede superar el saldo disponible
            $this->verificarSaldoSuficiente($cuenta, $datos['tipo'], $datos['monto']);

            Transaccion::create($datos);
            $this->aplicarEfecto($cuenta, $datos['tipo'], $datos['monto']);
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción registrada y saldo actualizado.');
    }

    public function edit(Transaccion $transaccion)
    {
        return view('transacciones.edit', [
            'transaccion' => $transaccion,
            'cuentas' => Cuenta::orderBy('nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }


    public function update(Request $request, Transaccion $transaccion)
    {
        $datos = $this->validar($request);

        DB::transaction(function () use ($datos, $transaccion) {
            // 1) Revertir el efecto anterior en la cuenta original
            $cuentaAnterior = Cuenta::lockForUpdate()->findOrFail($transaccion->cuenta_id);
            $this->revertirEfecto($cuentaAnterior, $transaccion->tipo, $transaccion->monto);

            // 2) Leer la cuenta nueva (puede ser la misma) con el saldo ya revertido
            $cuentaNueva = Cuenta::lockForUpdate()->findOrFail($datos['cuenta_id']);

            // 3) Si ahora es gasto, no puede superar el saldo disponible
            $this->verificarSaldoSuficiente($cuentaNueva, $datos['tipo'], $datos['monto']);

            // 4) Aplicar el nuevo efecto y guardar los cambios
            $this->aplicarEfecto($cuentaNueva, $datos['tipo'], $datos['monto']);
            $transaccion->update($datos);

            // 5) La cuenta original no puede quedar con saldo negativo
            $this->verificarSaldoNoNegativo($cuentaAnterior);
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción actualizada y saldos recalculados.');
    }

    public function destroy(Transaccion $transaccion)
    {
        $cuenta = $transaccion->cuenta;

        // Si se borra un ingreso que ya se gastó, la cuenta quedaría en negativo
        if ($transaccion->tipo === 'ingreso' && (float) $cuenta->saldo < (float) $transaccion->monto) {
            return redirect()->route('transacciones.index')
                ->with('error', 'No se puede eliminar este ingreso: la cuenta "' . $cuenta->nombre . '" quedaría con saldo negativo.');
        }

        DB::transaction(function () use ($transaccion, $cuenta) {
            $this->revertirEfecto($cuenta, $transaccion->tipo, $transaccion->monto);
            $transaccion->delete();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción eliminada y saldo actualizado.');
    }


    private function aplicarEfecto(Cuenta $cuenta, string $tipo, $monto): void
    {
        if ($tipo === 'ingreso') {
            $cuenta->increment('saldo', $monto);
        } else {
            $cuenta->decrement('saldo', $monto);
        }
    }

    private function revertirEfecto(Cuenta $cuenta, string $tipo, $monto): void
    {
        if ($tipo === 'ingreso') {
            $cuenta->decrement('saldo', $monto);
        } else {
            $cuenta->increment('saldo', $monto);
        }
    }

    private function verificarSaldoSuficiente(Cuenta $cuenta, string $tipo, $monto): void
    {
        if ($tipo === 'gasto' && (float) $monto > (float) $cuenta->saldo) {
            throw ValidationException::withMessages([
                'monto' => 'El gasto supera el saldo disponible de la cuenta "' . $cuenta->nombre
                    . '" ($ ' . number_format((float) $cuenta->saldo, 2, ',', '.') . ').',
            ]);
        }
    }

    private function verificarSaldoNoNegativo(Cuenta $cuenta): void
    {
        $cuenta->refresh();

        if ((float) $cuenta->saldo < 0) {
            throw ValidationException::withMessages([
                'monto' => 'No se puede guardar el cambio: la cuenta "' . $cuenta->nombre . '" quedaría con saldo negativo.',
            ]);
        }
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'cuenta_id' => ['required', 'exists:cuentas,id'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'tipo' => ['required', 'in:ingreso,gasto'],
            'monto' => ['required', 'numeric', 'gt:0', 'max:9999999999999'],
            'fecha' => ['required', 'date'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ], [
            'cuenta_id.required' => 'Debes seleccionar una cuenta.',
            'cuenta_id.exists' => 'La cuenta seleccionada no existe.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'tipo.required' => 'Debes seleccionar el tipo (ingreso o gasto).',
            'tipo.in' => 'El tipo debe ser ingreso o gasto.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.gt' => 'El monto debe ser mayor que cero.',
            'monto.max' => 'El monto es demasiado grande.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        $categoria = Categoria::find($datos['categoria_id']);

        if ($categoria->tipo !== $datos['tipo']) {
            throw ValidationException::withMessages([
                'categoria_id' => 'La categoría "' . $categoria->nombre . '" es de ' . $categoria->tipo
                    . '; solo se puede usar en transacciones de tipo ' . $categoria->tipo . '.',
            ]);
        }

        return $datos;
    }
}
