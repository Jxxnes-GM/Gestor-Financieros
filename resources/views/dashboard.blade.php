<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel principal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tarjetas de resumen -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total de ingresos</p>
                    <p class="mt-1 text-2xl font-semibold text-green-700">$ {{ number_format((float) $totalIngresos, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total de gastos</p>
                    <p class="mt-1 text-2xl font-semibold text-red-700">$ {{ number_format((float) $totalGastos, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Categorías / Cuentas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-800">{{ $totalCategorias }} / {{ $cuentas->count() }}</p>
                </div>
            </div>

            <!-- Saldo de cada cuenta -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Saldo por cuenta</h3>
                        <a href="{{ route('cuentas.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Ver cuentas</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Cuenta</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Moneda</th>
                                    <th class="px-4 py-2 text-right font-semibold text-gray-600">Saldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($cuentas as $cuenta)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-800">{{ $cuenta->nombre }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $cuenta->moneda }}</td>
                                        <td class="px-4 py-2 text-right font-semibold text-gray-800">$ {{ number_format((float) $cuenta->saldo, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Todavía no hay cuentas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Últimos movimientos -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Últimas transacciones</h3>
                        <a href="{{ route('transacciones.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">+ Registrar transacción</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Cuenta</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Categoría</th>
                                    <th class="px-4 py-2 text-right font-semibold text-gray-600">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($ultimas as $transaccion)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-600">{{ $transaccion->fecha->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $transaccion->cuenta->nombre }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $transaccion->categoria->nombre }}</td>
                                        <td class="px-4 py-2 text-right font-semibold {{ $transaccion->tipo === 'ingreso' ? 'text-green-700' : 'text-red-700' }}">
                                            {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }} $ {{ number_format((float) $transaccion->monto, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Todavía no hay transacciones.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>