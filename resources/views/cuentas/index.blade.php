<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cuentas
            </h2>
            <a href="{{ route('cuentas.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                + Nueva cuenta
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
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Descripción</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Moneda</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Saldo actual</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Movimientos</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($cuentas as $cuenta)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $cuenta->nombre }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $cuenta->descripcion }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $cuenta->moneda }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-800 whitespace-nowrap">
                                        $ {{ number_format((float) $cuenta->saldo, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $cuenta->transacciones_count }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('cuentas.edit', $cuenta) }}" class="font-medium text-indigo-600 hover:text-indigo-900">Editar</a>

                                        <form action="{{ route('cuentas.destroy', $cuenta) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta cuenta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ms-3 font-medium text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay cuentas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
