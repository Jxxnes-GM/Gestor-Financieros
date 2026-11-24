{{-- Formulario compartido por "crear" y "editar". old() conserva los datos si la validación falla. --}}
<div class="space-y-6">
    <!-- Nombre -->
    <div>
        <x-input-label for="nombre" value="Nombre" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
                      :value="old('nombre', $cuenta->nombre)" autofocus />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>

    <!-- Descripción -->
    <div>
        <x-input-label for="descripcion" value="Descripción (opcional)" />
        <textarea id="descripcion" name="descripcion" rows="3"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion', $cuenta->descripcion) }}</textarea>
        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
    </div>

    <!-- Saldo -->
    <div>
        <x-input-label for="saldo" :value="$cuenta->exists ? 'Saldo actual' : 'Saldo inicial'" />
        <x-text-input id="saldo" name="saldo" type="number" step="0.01" class="mt-1 block w-full"
                      :value="old('saldo', $cuenta->saldo)" />
        @if ($cuenta->exists)
            <p class="mt-1 text-xs text-gray-500">El saldo se actualiza solo con cada ingreso o gasto. Cámbialo aquí únicamente para un ajuste manual.</p>
        @endif
        <x-input-error :messages="$errors->get('saldo')" class="mt-2" />
    </div>

    <!-- Moneda -->
    <div>
        <x-input-label for="moneda" value="Moneda" />
        <select id="moneda" name="moneda"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            @foreach (['COP' => 'COP - Peso colombiano', 'USD' => 'USD - Dólar', 'EUR' => 'EUR - Euro'] as $codigo => $texto)
                <option value="{{ $codigo }}" @selected(old('moneda', $cuenta->moneda) === $codigo)>{{ $texto }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('moneda')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>Guardar</x-primary-button>
        <a href="{{ route('cuentas.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</a>
    </div>
</div>
