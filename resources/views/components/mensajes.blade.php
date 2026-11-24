{{-- Mensajes de confirmación (verde) y de error (rojo) que mandan los controladores --}}
@if (session('success') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        @if (session('success'))
            <div class="rounded-md border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endif
