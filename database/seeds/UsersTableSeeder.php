<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('usuario')
        ->insert([
          'username' => 'AdminGabo',
          'password' => bcrypt('PayrollAdmin00'),
          'nombre'  => 'Developers',
          'apellido' => 'Users',
          'email' => 'gabojr05@gmail.com',
          'rol' => 'admin',
          'estatus' => 'activo'
        ]);
    }
}
