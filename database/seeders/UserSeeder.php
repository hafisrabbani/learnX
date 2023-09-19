<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $make = 3;
        $roles = ['admin', 'dosen', 'mahasiswa'];
        $this->command->info("Creating {$make} users");
        for ($i = 0; $i < $make; $i++) {
            $this->command->info("Creating user {$i}");
            $user = \App\Models\User::factory()->create(); // pw = password
            $user->assignRole('admin');
        }
    }
}
