<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->insert([
            [
                'uid' => 1,
                'subject' => 'Subject 01',
                'detail' => 'Content 01',
            ],
            [
                'uid' => 2,
                'subject' => 'Subject 02',
                'detail' => 'Content 02',
            ]
        ]);
    }
}
