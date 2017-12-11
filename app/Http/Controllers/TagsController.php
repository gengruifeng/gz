<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\SearchTagsRepository;
use App\Repositories\QuestionsTaggedRepository;

class TagsController extends Controller
{
    /**
     * Search Tags
     *
     * @param void
     *
     * @return void
     */
    public function query(Request $request)
    {
        $permit = ['q'];
        $input = $request->only($permit);
        $repo = new SearchTagsRepository($input);
        $repo->contract();
        return response()->json($repo->biz);
    }

    /**
     * Tagged Questions
     *
     * @param Request $request
     * @param string $tag
     *
     * @return void
     */
    public function questions(Request $request, $tags)
    {
        $permit = ['page', 'tab'];
        $input = $request->only($permit);

        $input['tags'] = $tags;
        $input['type'] = 1;


        $repo = new QuestionsTaggedRepository($input);
        $repo->contract();

        if (404 === $repo->status) {
            return response()->view('errors.404', [], 404);
        }

        return view('questions.tagged', $repo->biz);
    }
    /**
     * Tagged Questions
     *
     * @param Request $request
     * @param string $tag
     *
     * @return void
     */
    public function questionslist(Request $request, $tags)
    {
        $permit = ['page', 'tab'];
        $input = $request->only($permit);

        $input['tags'] = $tags;
        $input['type'] = 2;

        $repo = new QuestionsTaggedRepository($input);
        $repo->contract();

        if (404 === $repo->status) {
            return response()->view('errors.404', [], 404);
        }

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return view('questions.tagged_list', $repo->biz);
    }
}
