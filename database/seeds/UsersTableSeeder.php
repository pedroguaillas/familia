<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Cesar Gualán',
            'email' => 'caguilar430@gmail.com',
            'password' => Hash::make('Contra*111')
        ]);

        DB::table('users')->insert([
            'name' => 'Jaime Gualán',
            'email' => 'jairamishey@gmail.com',
            'password' => Hash::make('Contra*111')
        ]);
    }
}
