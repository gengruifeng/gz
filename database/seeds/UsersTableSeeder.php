<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'display_name' => 'ABC 01',
                'passcode' => Hash::make('passcode')
            ],
            [
                'display_name' => 'ABC 02',
                'passcode' => Hash::make('passcode')
            ],
            [
                'display_name' => 'ABC 03',
                'passcode' => Hash::make('passcode')
            ]
        ]);
    }
}
