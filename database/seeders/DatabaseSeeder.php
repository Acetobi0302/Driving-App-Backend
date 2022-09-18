<?php

namespace Database\Seeders;

use App\Models\Franchise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $franchise = Franchise::create([
            'name' => 'admin',
            'address' => 'address',
        ]);

        $user = User::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'name' => 'admin',
            'role' => 'admin',
            'address' => 'address',
            'phone' => '9876543210',
            'franchise_id' => $franchise->id,

        ]);

    }
}
