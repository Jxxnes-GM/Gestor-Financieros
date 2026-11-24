<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Transacciones
            </h2>
            <a href="{{ route('transacciones.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                + Nueva transacción
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Fecha</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tipo</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Cuenta</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Categoría</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Descripción</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Monto</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Registrado por</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($transacciones as $transaccion)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $transaccion->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($transaccion->tipo === 'ingreso')
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800">Ingreso</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Gasto</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-800">{{ $transaccion->cuenta->nombre }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $transaccion->categoria->nombre }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $transaccion->descripcion }}</td>
                                    <td class="px-4 py-3 text-right font-semibold whitespace-nowrap {{ $transaccion->tipo === 'ingreso' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }} $ {{ number_format((float) $transaccion->monto, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $transaccion->creado_por }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('transacciones.edit', $transaccion) }}" class="font-medium text-indigo-600 hover:text-indigo-900">Editar</a>

                                        <form action="{{ route('transacciones.destroy', $transaccion) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta transacción? Se revertirá su efecto en el saldo.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ms-3 font-medium text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No hay transacciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $transacciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
