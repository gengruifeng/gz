<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\TagsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{

    public function taglist(){
        return view('admin.tagslist');
    }

    public function categories(){
        $categories = DB::table('categories')->orderBy('order','desc')->get();
        $tagAll = DB::table('tags')->orderBy('created_at','desc')->get();
        $TagsRepository = new TagsRepository();
        $categoriesTag = $TagsRepository->getCategoriesTag();
        return view('admin.tagscategories')->with('tagAll',$tagAll)->with('categories',$categories)->with('categoriesTag',$categoriesTag);
    }

}
