<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->truncate();
        $this->call(UsersTableSeeder::class);
        factory(App\User::class, 100)->create();
    }
}

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Administrator',
            'email' => 'vannguyen52345@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'type' => 1,
            'status' => 1
        ]);
    }
}
