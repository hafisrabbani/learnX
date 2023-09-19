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
        $role = ['admin', 'dosen', 'mahasiswa'];

        for ($i = 0; $i < count($role); $i++) {
            \App\Models\User::factory()->create([
                'role' => $role[$i],
                'password' => bcrypt('password'),
                'email' => $role[$i] . '@gmail.com',
            ]);

            $this->command->info("User {$role[$i]} berhasil dibuat");
        }
    }
}
