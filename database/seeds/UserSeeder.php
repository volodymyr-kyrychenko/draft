<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;

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
            'name' => Str::random(10),
            'email' => 'test1@mail.com',
            'password' => Hash::make('12345678'),
			'address' => Str::random(50),
        ]);
		
		User::create([
            'name' => Str::random(10),
            'email' => 'test2@mail.com',
            'password' => Hash::make('12345678'),
			'address' => Str::random(50),
        ]);
    }
}
