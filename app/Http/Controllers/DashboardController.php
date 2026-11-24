<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cuenta;
use App\Models\Transaccion;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'cuentas' => Cuenta::orderBy('nombre')->get(),
            'totalCategorias' => Categoria::count(),
            'totalIngresos' => Transaccion::where('tipo', 'ingreso')->sum('monto'),
            'totalGastos' => Transaccion::where('tipo', 'gasto')->sum('monto'),
            'ultimas' => Transaccion::with(['cuenta', 'categoria'])
                ->orderByDesc('fecha')
                ->orderByDesc('id')
                ->take(5)
                ->get(),
        ]);
    }
}
