<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar transacción
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="mb-6 text-sm text-gray-500">
                    Al guardar, se revierte el efecto anterior sobre el saldo y se aplica el nuevo
                    (aunque cambies la cuenta o el tipo).
                </p>
                <form method="POST" action="{{ route('transacciones.update', $transaccion) }}">
                    @csrf
                    @method('PUT')
                    @include('transacciones._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
