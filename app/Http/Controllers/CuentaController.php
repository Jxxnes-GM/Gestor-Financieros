<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    /**
     * Listado de cuentas con su saldo actualizado.
     */
    public function index()
    {
        $cuentas = Cuenta::withCount('transacciones')
            ->orderBy('nombre')
            ->get();

        return view('cuentas.index', compact('cuentas'));
    }

    public function create()
    {
        return view('cuentas.create', ['cuenta' => new Cuenta(['saldo' => 0, 'moneda' => 'COP'])]);
    }

    public function store(Request $request)
    {
        Cuenta::create($this->validar($request));

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta registrada correctamente.');
    }

    public function edit(Cuenta $cuenta)
    {
        return view('cuentas.edit', compact('cuenta'));
    }

    public function update(Request $request, Cuenta $cuenta)
    {
        $cuenta->update($this->validar($request));

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    /**
     * No se permite eliminar una cuenta que tenga movimientos.
     */
    public function destroy(Cuenta $cuenta)
    {
        if ($cuenta->transacciones()->exists()) {
            return redirect()->route('cuentas.index')
                ->with('error', 'No se puede eliminar la cuenta "' . $cuenta->nombre . '" porque tiene movimientos asociados.');
        }

        $cuenta->delete();

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta eliminada correctamente.');
    }

    /**
     * Validaciones del formulario.
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'saldo' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
            'moneda' => ['required', 'in:COP,USD,EUR'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 150 caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
            'saldo.required' => 'El saldo es obligatorio (puede ser 0).',
            'saldo.numeric' => 'El saldo debe ser un número.',
            'saldo.min' => 'El saldo no puede ser negativo.',
            'saldo.max' => 'El saldo es demasiado grande.',
            'moneda.required' => 'Debes seleccionar la moneda.',
            'moneda.in' => 'La moneda debe ser COP, USD o EUR.',
        ]);
    }
}
