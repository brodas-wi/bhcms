<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin1234'),
        ]);
        $admin->assignRole('admin');

        // Editor
        $editor = User::create([
            'name' => 'editor',
            'email' => 'editor@editor.com',
            'password' => Hash::make('editor1234'),
        ]);
        $editor->assignRole('editor');

        // Lista de nombres para usuarios
        $names = [
            'Juan',
            'Pedro',
            'Maria',
            'Luisa',
            'Carlos',
            'Ana',
            'Roberto',
            'Carmen',
            'Jose',
            'Patricia',
            'Sofia',
            'Miguel',
            'Javier',
            'Laura',
            'Daniela',
            'Martin',
            'Alejandro',
            'Gabriela',
            'Fernando',
            'Paula'
        ];

        // Roles disponibles
        $roles = ['editor', 'user'];

        // Otros usuarios
        foreach ($names as $name) {
            $randomRole = $roles[array_rand($roles)];
            $user = User::create([
                'name' => $name,
                'email' => strtolower($name) . '@example.com',
                'password' => Hash::make('hola1234'),
            ]);
            $user->assignRole($randomRole);
        }
    }
}
