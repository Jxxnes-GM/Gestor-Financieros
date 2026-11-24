<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Listado de categorías.
     */
    public function index()
    {
        $categorias = Categoria::withCount('transacciones')
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create', ['categoria' => new Categoria()]);
    }

    public function store(Request $request)
    {
        Categoria::create($this->validar($request));

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $datos = $this->validar($request);

        // Si ya tiene movimientos, no se puede cambiar de ingreso a gasto (o al revés)
        if ($datos['tipo'] !== $categoria->tipo && $categoria->transacciones()->exists()) {
            return back()->withInput()->withErrors([
                'tipo' => 'No se puede cambiar el tipo porque la categoría ya tiene transacciones asociadas.',
            ]);
        }

        $categoria->update($datos);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * No se permite eliminar una categoría que tenga transacciones.
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->transacciones()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar la categoría "' . $categoria->nombre . '" porque tiene transacciones asociadas.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Validaciones del formulario.
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'tipo' => ['required', 'in:ingreso,gasto'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 150 caracteres.',
            'tipo.required' => 'Debes seleccionar el tipo (ingreso o gasto).',
            'tipo.in' => 'El tipo debe ser ingreso o gasto.',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);
    }
}
