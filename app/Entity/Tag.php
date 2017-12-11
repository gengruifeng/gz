<?php

namespace App\Entity;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use DB;
use Log;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The table associated with the entity
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * Get existing tags ID or create and index tags if they does not exist
     *
     * @param array $tagNames
     *
     * @return array $id
     */
    public static function getTagsId(array $tagNames, $uid)
    {
        $tagIds = $new = $existing = [];

        $tags = DB::select('SELECT id, name FROM tags WHERE name IN('.sprintf('\'%s\'', implode('\',\'', $tagNames)).')  LIMIT :limit', ['limit' => count($tagNames)]);
        foreach ($tagNames as $key=>$val){
            $tagNames[$key] = strtolower($val);
        }

        $tagNames = array_unique($tagNames);

        foreach ($tags as $tag) {
            $tagIds[] = $tag->id;
            $existing[] = strtolower($tag->name);

        }

        $new = array_diff($tagNames, $existing);

        // Add new tags to MySQL and Sphinx
        if (! empty($new)) {
            $datetime = date('Y-m-d H:i:s');

            $entries = [];
            foreach ($new as $tagName) {
                $entries[] = [
                    'uid' => $uid,
                    'name' => $tagName,
                    'created_at' => $datetime,
                    'updated_at' => $datetime
                ];
            }
            DB::table('tags')->insert($entries);

            $tags = DB::select('SELECT id, name FROM tags WHERE name IN('.sprintf('\'%s\'', implode('\',\'', $new)).')  LIMIT :limit', ['limit' => count($new)]);

            $connection = new Connection();
            $connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);

//            $sphinx = SphinxQL::create($connection)->insert()->into('tags')->columns('id', 'name');

            foreach ($tags as $tag) {
                $tagIds[] = $tag->id;
                $ret = SphinxQL::create($connection)->query("select * from tags where id=".$tag->id)->execute();
                if(empty($ret)){
                    SphinxQL::create($connection)->query("insert into tags (id,name) values ({$tag->id},'".$tag->name."')")->execute();
//                    $sphinx->values($tag->id, $tag->name);
                }else{
                    $sq = SphinxQL::create($connection)->replace()->into('tags');
                    $sq->value('id', $tag->id)->value('name', $tag->name);
                    $sq->execute();
                }
            }

//            $sphinx->execute();
        }

        return $tagIds;
    }
}
