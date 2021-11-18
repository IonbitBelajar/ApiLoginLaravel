<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'nurfirdaus.5000@gmail.com',
            'name' => 'NurFirdausRamandani',
            'password' => \Hash::make('password'),
            'status' => 'aktif',
        ]);
    }
}
