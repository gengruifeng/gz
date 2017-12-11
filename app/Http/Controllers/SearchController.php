<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\SearchQuestionsRepository;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SearchController extends Controller
{
    /**
     * Search articles, tags and users based on the query
     *
     * @param void
     *
     * @return void
     */
    public function query(Request $request)
    {

        $permit = ['q', 'page', 'tab'];
        $input = $request->only($permit);

        $input['async'] = 1;

        $repo = new SearchQuestionsRepository($input);
        $repo->contract();

        return view('search', $repo->biz);
    }
    /**
     * Search articles, tags and users based on the query
     *
     * @param void
     *
     * @return void
     */
    public function queryajax(Request $request)
    {
        $permit = ['q', 'page', 'tab'];
        $input = $request->only($permit);

        $input['async'] = 2;

        $repo = new SearchQuestionsRepository($input);
        $repo->contract();

        return response()->json($repo->biz['questions']);
    }

    /**
     * Search articles, tags and users based on the query
     *
     * @param void
     *
     * @return void
     */
    public function searchlist(Request $request)
    {
        header('Expires: 0');
        header('Last-Modified: '. gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cahe, must-revalidate');
        $permit = ['q', 'page', 'tab'];
        $input = $request->only($permit);
        $input['async'] = 3;
        $repo = new SearchQuestionsRepository($input);
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return view('search_list', $repo->biz);
    }
    /**
     * Search latest items
     *
     * @param void
     *
     * @return void
     */
    public function trends(Request $request)
    {
        $trend = [
            [
                'source_id' => 'trends',
                'label' => 'Trends',
                'value' => 'value',
                'id' => 123,
            ],
        ];

        return response()->json($trend);
    }
}
