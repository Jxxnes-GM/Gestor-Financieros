<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cuenta;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Datos de prueba (los mismos del archivo database/gestor_financiero.sql).
 * Se usa solo si quieren reiniciar la base con:  php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@gestor.com',
            'password' => Hash::make('admin12345'),
        ]);

        // 5 categorías: 2 de ingreso y 3 de gasto
        $salario   = Categoria::create(['nombre' => 'Salario', 'tipo' => 'ingreso', 'descripcion' => 'Pago mensual de nómina']);
        $ventas    = Categoria::create(['nombre' => 'Ventas', 'tipo' => 'ingreso', 'descripcion' => 'Ingresos por ventas o trabajos independientes']);
        $comida    = Categoria::create(['nombre' => 'Alimentación', 'tipo' => 'gasto', 'descripcion' => 'Mercado y comidas']);
        $transporte = Categoria::create(['nombre' => 'Transporte', 'tipo' => 'gasto', 'descripcion' => 'Pasajes, gasolina y taxis']);
        $servicios = Categoria::create(['nombre' => 'Servicios públicos', 'tipo' => 'gasto', 'descripcion' => 'Agua, luz, gas e internet']);

        // 3 cuentas. Los saldos ya incluyen el efecto de las transacciones de abajo:
        //   Efectivo:          100.000 + 350.000 - 120.000 - 45.000 =   285.000
        //   Cuenta de ahorros: 500.000 + 2.500.000 - 180.000        = 2.820.000
        //   Cuenta corriente:  200.000 (sin movimientos, lista para la prueba 200.000 -> 330.000)
        $efectivo  = Cuenta::create(['nombre' => 'Efectivo', 'descripcion' => 'Dinero en efectivo', 'saldo' => 285000, 'moneda' => 'COP']);
        $ahorros   = Cuenta::create(['nombre' => 'Cuenta de ahorros', 'descripcion' => 'Cuenta de ahorros principal', 'saldo' => 2820000, 'moneda' => 'COP']);
        Cuenta::create(['nombre' => 'Cuenta corriente', 'descripcion' => 'Cuenta para pagos', 'saldo' => 200000, 'moneda' => 'COP']);

        // 5 transacciones: 2 ingresos y 3 gastos
        $movimientos = [
            [$ahorros,  $salario,    'ingreso', 2500000, '2026-11-01', 'Salario de noviembre'],
            [$efectivo, $ventas,     'ingreso', 350000,  '2026-11-05', 'Venta de artículos usados'],
            [$efectivo, $comida,     'gasto',   120000,  '2026-11-08', 'Mercado de la semana'],
            [$efectivo, $transporte, 'gasto',   45000,   '2026-11-10', 'Recarga tarjeta de transporte'],
            [$ahorros,  $servicios,  'gasto',   180000,  '2026-11-15', 'Pago de luz y agua'],
        ];

        foreach ($movimientos as [$cuenta, $categoria, $tipo, $monto, $fecha, $descripcion]) {
            Transaccion::create([
                'cuenta_id' => $cuenta->id,
                'categoria_id' => $categoria->id,
                'tipo' => $tipo,
                'monto' => $monto,
                'fecha' => $fecha,
                'descripcion' => $descripcion,
                'creado_por' => 'Administrador',
            ]);
        }
    }
}
