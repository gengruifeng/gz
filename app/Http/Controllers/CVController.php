<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Validator;
use View;

use App\Entity\CVTemplate;
use App\Entity\CVProfession;

use App\Repositories\CVTemplateIndexRepository;
use App\Repositories\CVTemplateSearchRepository;
use App\Utils\HttpUserAgent;

class CVController extends Controller
{
    /**
     * Articles
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        $permit = ['page', 'language', 'colorscheme', 'profession', 'tab'];
        $input = $request->only($permit);
        $input['type'] = 1;

        $rules = [
            'page' => 'integer',
            'profession' => 'integer',
            'language' => 'in:zh-cn,en-us',
            'colorscheme' => 'integer',
            'tab' => 'in:latest,trending'
        ];

        $validator = Validator::make($input, $rules);
        $valid = $validator->valid();

        $repo = new CVTemplateIndexRepository($valid);
        $repo->contract();
        if(HttpUserAgent::isMobile()){
            return view('html5/cv/templates/resume', $repo->biz);
        }else{
            return view('cv/templates/index', $repo->biz);
        }
    }
    /**
     * Articles
     *
     * @param Request $request
     *
     * @return View
     */
    public function templateslist(Request $request)
    {
        $permit = ['page', 'language', 'colorscheme', 'profession', 'tab'];
        $input = $request->only($permit);
        $input['type'] = 2;

        $rules = [
            'page' => 'integer',
            'profession' => 'integer',
            'language' => 'in:zh-cn,en-us',
            'colorscheme' => 'integer',
            'tab' => 'in:latest,trending'
        ];

        $validator = Validator::make($input, $rules);
        $valid = $validator->valid();
        $repo = new CVTemplateIndexRepository($valid);
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        if(HttpUserAgent::isMobile()){
            return view('html5/cv/templates/list', $repo->biz);
        }else{
            return view('cv/templates/list', $repo->biz);
        }
    }
    /**
     * Search Templates
     *
     * @param Request $request
     *
     * @return View
     */
    public function search(Request $request)
    {
        $permit = ['q', 'page', 'language', 'colorscheme', 'profession', 'tab'];
        $input = $request->only($permit);

        $input['async'] = false;

        if ($request->ajax()) {
            $input['async'] = true;
        }

        if (empty($input['q'])) {
            return redirect('cv/templates');
        }

        $rules = [
            'page' => 'integer',
            'profession' => 'integer',
            'language' => 'in:zh-cn,en-us',
            'colorscheme' => 'integer',
            'tab' => 'in:latest,trending'
        ];

        $validator = Validator::make($input, $rules);
        $valid = $validator->valid();

        $valid['q'] = $input['q'];

        $repo = new CVTemplateSearchRepository($valid);
        $repo->contract();

        if ($request->ajax()) {
            return response()->json($repo->biz['templates']);
        }
        if(HttpUserAgent::isMobile()){
            return view('html5/cv/templates/search', $repo->biz);
        }else {
            return view('cv/templates/search', $repo->biz);
        }
    }
    /**
     * Search Templates
     *
     * @param Request $request
     *
     * @return View
     */
    public function searchlist(Request $request)
    {
        $permit = ['q', 'page', 'language', 'colorscheme', 'profession', 'tab'];
        $input = $request->only($permit);

        $input['async'] = true;

        if (empty($input['q'])) {
            return redirect('cv/templates');
        }

        $rules = [
            'page' => 'integer',
            'profession' => 'integer',
            'language' => 'in:zh-cn,en-us',
            'colorscheme' => 'integer',
            'tab' => 'in:latest,trending'
        ];

        $validator = Validator::make($input, $rules);
        $valid = $validator->valid();

        $valid['q'] = $input['q'];

        $repo = new CVTemplateSearchRepository($valid);
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        if(HttpUserAgent::isMobile()){
            return view('html5/cv/templates/list', $repo->biz);
        }else {
            return view('cv/templates/list', $repo->biz);
        }
    }
    /**
     * Preview Template
     *
     * @param Request $request
     *
     * @return file
     */
    public function preview(Request $request, $template)
    {
        $template = CVTemplate::find($template, ['id', 'subject', 'preview', 'downloaded']);

        if (null === $template) {
            throw new NotFoundHttpException();
        }

        if(HttpUserAgent::isMobile()){
            return view('html5/cv/templates/preview', ['template' => $template]);
        }else {
            return view('cv/templates/preview', ['template' => $template]);
        }
    }

    /**
     * Download Templates
     *
     * @param Request $request
     *
     * @return file
     */
    public function download(Request $request, $template)
    {
        $template = CVTemplate::find($template, ['id', 'subject', 'file', 'downloaded']);

        if (null === $template) {
            throw new NotFoundHttpException();
        }

        if (! file_exists(sprintf(public_path().'/templates/file/%s', $template->file))) {
            throw new NotFoundHttpException();
        }

        // Increase Download Count
        $template->increment('downloaded', 1);

        $uid = $request->security()->get('uid');
        if (0 === (int) $uid) {
            throw new UnauthorizedHttpException('Basic realm="My Realm"');
        }
        $agent=$_SERVER["HTTP_USER_AGENT"];
        if(strpos($agent,'MSIE')  || strpos($agent,'rv:11.0') ){
            $template->subject = urlencode($template->subject);
        }
        return response('')->withHeaders([
            'X-Accel-Redirect' => sprintf('/templates/file/%s', $template->file),
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => sprintf('attachment; filename=%s.'.substr($template->file, strrpos($template->file, '.') + 1), $template->subject),
        ]);
    }


    /**
     * Send TemplateUrl To Email
     *
     * @param Request $request
     *
     * @return View
     */
    public function email()
    {
        $url = $_SERVER['HTTP_REFERER'];
        return view('html5/cv/templates/email',['url'=>$url]);   
    }
}
