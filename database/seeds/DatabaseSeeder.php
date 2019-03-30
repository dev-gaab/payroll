<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
     * Seed the application's database.
     *
     * @return void
     */
  public function run()
  {
    $this->call(UsersTableSeeder::class);

    DB::table('empresa')
      ->insert([
        'rif' => 'V123456789',
        'razon_social' => 'Empresa de prueba, C.A',
        'direccion'  => 'Carupano',
        'riesgo_ivss' => 'minimo',
        'estatus' => 'activa'
      ]);

    DB::table('asignaciones')
      ->insert([
        'he_diurnas' => 1.5,
        'he_nocturnas' => 1.8,
        'feriados' => 1.5,
        'desde' => '01-01-2019',
        'estatus' => 'activa'
      ]);

    DB::table('deducciones')
      ->insert([
        'ivss' => 4,
        'faov' => 1,
        'paro_forzoso' => 0.5,
        'desde' => '01-01-2019',
        'estatus' => 'activa'
      ]);

    DB::table('cesta_ticket')
      ->insert([
        'cantidad' => '1800',
        'desde' => '01-01-2019',
        'estatus' => 'activa'
      ]);

    DB::table('salario_minimo')
      ->insert([
        'monto' => 18000,
        'tipo' => 'mensual',
        'desde' => '01-01-2019',
        'estatus' => 'activa'
      ]);
  }
}
