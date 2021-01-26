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
        // $this->call(UsersTableSeeder::class);
        \Illuminate\Support\Facades\DB::table('users')->insert([
            ['name'=>'abc','email'=>'user@gmail.com','password'=>bcrypt('123456'),'role_id'=>'2','phone'=>'0123456789']
            ]);
    }
}
