{{-- Formulario compartido por "crear" y "editar". old() conserva los datos si la validación falla. --}}
<div class="space-y-6">
    @if ($cuentas->isEmpty() || $categorias->isEmpty())
        <div class="rounded-md border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
            Antes de registrar transacciones necesitas al menos una
            <a href="{{ route('cuentas.create') }}" class="underline">cuenta</a> y una
            <a href="{{ route('categorias.create') }}" class="underline">categoría</a>.
        </div>
    @endif

    <!-- Tipo -->
    <div>
        <x-input-label for="tipo" value="Tipo" />
        <select id="tipo" name="tipo"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">-- Selecciona --</option>
            <option value="ingreso" @selected(old('tipo', $transaccion->tipo) === 'ingreso')>Ingreso</option>
            <option value="gasto" @selected(old('tipo', $transaccion->tipo) === 'gasto')>Gasto</option>
        </select>
        <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
    </div>

    <!-- Cuenta -->
    <div>
        <x-input-label for="cuenta_id" value="Cuenta" />
        <select id="cuenta_id" name="cuenta_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">-- Selecciona --</option>
            @foreach ($cuentas as $cuenta)
                <option value="{{ $cuenta->id }}" @selected(old('cuenta_id', $transaccion->cuenta_id) == $cuenta->id)>
                    {{ $cuenta->nombre }} (saldo: $ {{ number_format((float) $cuenta->saldo, 2, ',', '.') }} {{ $cuenta->moneda }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('cuenta_id')" class="mt-2" />
    </div>

    <!-- Categoría (agrupadas por tipo) -->
    <div>
        <x-input-label for="categoria_id" value="Categoría" />
        <select id="categoria_id" name="categoria_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">-- Selecciona --</option>
            <optgroup label="Categorías de ingreso">
                @foreach ($categorias->where('tipo', 'ingreso') as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id', $transaccion->categoria_id) == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </optgroup>
            <optgroup label="Categorías de gasto">
                @foreach ($categorias->where('tipo', 'gasto') as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id', $transaccion->categoria_id) == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </optgroup>
        </select>
        <p class="mt-1 text-xs text-gray-500">Una categoría de ingreso solo sirve para ingresos y una de gasto solo para gastos.</p>
        <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
    </div>

    <!-- Monto -->
    <div>
        <x-input-label for="monto" value="Monto" />
        <x-text-input id="monto" name="monto" type="number" step="0.01" class="mt-1 block w-full"
                      :value="old('monto', $transaccion->monto)" placeholder="Ej: 150000" />
        <x-input-error :messages="$errors->get('monto')" class="mt-2" />
    </div>

    <!-- Fecha -->
    <div>
        <x-input-label for="fecha" value="Fecha" />
        <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full"
                      :value="old('fecha', $transaccion->fecha?->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
    </div>

    <!-- Descripción -->
    <div>
        <x-input-label for="descripcion" value="Descripción (opcional)" />
        <textarea id="descripcion" name="descripcion" rows="3"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion', $transaccion->descripcion) }}</textarea>
        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>Guardar</x-primary-button>
        <a href="{{ route('transacciones.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</a>
    </div>
</div>
