<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @created by adarshthavarool@gmail.com on (27 April 2022 at 11:23 PM)
     */
    public function run()
    {
        $user1 = User::create(['name' => 'Adarsh',
            'gender' => 'Male',
            'age' => '25',
            'email' => 'adarshthavarool@gmail.com',
            'password' => bcrypt('Password')]);

        Teacher::create(['user_id' => $user1->id]);

        $user2 = User::create(['name' => 'Abin',
            'gender' => 'Male',
            'age' => '27',
            'email' => 'abin@sm.com',
            'password' => bcrypt('Password')]);

        Teacher::create(['user_id' => $user2->id]);

        $user3 = User::create(['name' => 'Jilu',
            'gender' => 'Female',
            'age' => '29',
            'email' => 'jilu@sm.com',
            'password' => bcrypt('Password')]);

        Teacher::create(['user_id' => $user3->id]);

        $user4 = User::create(['name' => 'Jerin',
            'gender' => 'Male',
            'age' => '25',
            'email' => 'jerin@sm.com',
            'password' => bcrypt('Password')]);

        Teacher::create(['user_id' => $user4->id]);
    }
}
