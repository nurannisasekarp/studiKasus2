<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'username' => 'marsya ananda',
        //     'email' => 'marsya@gmail.com',
        //     'password' => Hash::make('1234'),
        //     'role' => 'admin',
        
        // ]);

        User::create([
            'username' => 'marsyaya',
            'email' => 'marsyaya@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'admin',
        
        ]);
    }
}
