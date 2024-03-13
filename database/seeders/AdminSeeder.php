<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Administrador Padrão",
            "email" => "noc@grupocednet.com.br",
            "password" => Hash::make('admin'),
            "username" => "admin"
        ]);
    }
}
