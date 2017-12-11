<?php

use Illuminate\Database\Seeder;

class ArticleCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article_comments')->insert([
            [
                'uid' => 2,
                'article_id' => 1,
                'content' => 'I Love Russia 01',
            ],
            [
                'uid' => 2,
                'article_id' => 1,
                'content' => 'I Love Russia 02',
            ]
        ]);
    }
}
