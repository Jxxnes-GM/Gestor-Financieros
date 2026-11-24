<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorías
            </h2>
            <a href="{{ route('categorias.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                + Nueva categoría
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
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tipo</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Descripción</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Transacciones</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($categorias as $categoria)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $categoria->nombre }}</td>
                                    <td class="px-4 py-3">
                                        @if ($categoria->tipo === 'ingreso')
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800">Ingreso</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Gasto</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $categoria->descripcion }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $categoria->transacciones_count }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('categorias.edit', $categoria) }}" class="font-medium text-indigo-600 hover:text-indigo-900">Editar</a>

                                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ms-3 font-medium text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No hay categorías registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
