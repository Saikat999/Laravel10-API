<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=[
            ['name'=>'Saikat','email'=>'saikat@gmail.com','password'=>'123456'],
            ['name'=>'Nishat','email'=>'nishat@gmail.com','password'=>'123456'],
            ['name'=>'Anik','email'=>'anik@gmail.com','password'=>'123456'],
            ['name'=>'Jim','email'=>'jim@gmail.com','password'=>'123456'],
            ['name'=>'Pavel','email'=>'pavel@gmail.com','password'=>'123456']
        ];
        
        User::insert($users);
    }
}
