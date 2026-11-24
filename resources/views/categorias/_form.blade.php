{{-- Formulario compartido por "crear" y "editar". old() conserva los datos si la validación falla. --}}
<div class="space-y-6">
    <!-- Nombre -->
    <div>
        <x-input-label for="nombre" value="Nombre" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
                      :value="old('nombre', $categoria->nombre)" autofocus />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>

    <!-- Tipo -->
    <div>
        <x-input-label for="tipo" value="Tipo" />
        <select id="tipo" name="tipo"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">-- Selecciona --</option>
            <option value="ingreso" @selected(old('tipo', $categoria->tipo) === 'ingreso')>Ingreso</option>
            <option value="gasto" @selected(old('tipo', $categoria->tipo) === 'gasto')>Gasto</option>
        </select>
        <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
    </div>

    <!-- Descripción -->
    <div>
        <x-input-label for="descripcion" value="Descripción (opcional)" />
        <textarea id="descripcion" name="descripcion" rows="3"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion', $categoria->descripcion) }}</textarea>
        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>Guardar</x-primary-button>
        <a href="{{ route('categorias.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</a>
    </div>
</div>
