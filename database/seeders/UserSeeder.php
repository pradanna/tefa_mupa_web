<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/users.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('user.json not found.');
            return;
        }
        $users = json_decode(File::get($jsonPath), true);

        foreach ($users as $userData) {
            // Jika password ada di json, hash jika belum di-hash
            if (isset($userData['password'])) {
                if (!str_starts_with($userData['password'], '$2y$')) {
                    $userData['password'] = Hash::make($userData['password']);
                }
            }
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
