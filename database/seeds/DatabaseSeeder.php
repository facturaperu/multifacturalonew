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
        App\Models\System\User::create([
            'name' => 'Admin Instrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);


        App\Models\System\Plan::create([
            'name' => 'Ilimitado',
            'pricing' =>  99,
            'limit_users' => 9999999999,
            'limit_documents' =>  9999999999,
            'documents_active' => ['Facturas, boletas, notas de débito y crédito, resúmenes y anulaciones','Guias de remisión','Retenciones','Percepciones'],
            'locked' => true
        ]);
    }
}
