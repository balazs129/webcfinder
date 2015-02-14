<?php

class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();

        User::create(array(
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'organization' => 'Test Organization',
            'password' => Hash::make('rpw'),
        ));
    }
}

