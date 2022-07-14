<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        DB::table('users')->insert([
            'name' => "Thành Trung",
            'email' => 'thanhtrung@gmail.com',
            'level'=> 1,
            'password' => Hash::make('201002'),
        ]);
    }
}
