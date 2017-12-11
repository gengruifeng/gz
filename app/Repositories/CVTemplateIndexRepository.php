<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;
use App\Utils\Email;

use App\Utils\HttpStatus;
use App\Utils\Computing;
use App\Utils\Pagination;

class CVTemplateIndexRepository extends Repository implements RepositoryInterface
{
    /**
     * 简历模版列表
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $page = 1 < (int) $this->input['page'] ? $this->input['page'] : 1;
        $templates = DB::table('cv_templates');

        // 附加已选 Query String
        $query = [];

        if (! empty($this->input['profession'])) {
            $templates->where('profession_id', $this->input['profession']);
            $query['profession'] = '&profession='.$this->input['profession'];
        }

        if (! empty($this->input['language'])) {
            $templates->where('language', $this->input['language']);
            $query['language'] = '&language='.$this->input['language'];
        }

        if ('' !== $this->input['colorscheme'] && null !== $this->input['colorscheme']) {
            $templates->where('colorscheme', $this->input['colorscheme']);
            $query['colorscheme'] = '&colorscheme='.$this->input['colorscheme'];
        }

        if ('trending' === $this->input['tab']) {
            $templates->orderBy('downloaded', 'desc')->orderBy('updated_at','desc');
            $query['tab'] = '&tab='.$this->input['tab'];
        } else {
            $templates->orderBy('updated_at', 'desc');
            $query['tab'] = '&tab='.'latest';
        }

        if($this->input['type'] === 2 ){
            if (! empty($this->input['page'])) {
                if (1 < $page) {
                    $templates->skip(($page - 1) * 10);
                }

            }
            $templates->take(10);
            $result = $templates->get(['id', 'subject', 'feature', 'preview', 'downloaded']);
            if(empty($result)){
                $this->accepted = false;
                $this->status = 404;
                $this->description = '暂无数据';
            }
            $this->biz = [
                'templates' => $result,
            ];
        }
        if($this->input['type'] === 1 ){
            $this->biz = [
                'professions' => DB::select('SELECT id, title FROM cv_professions'),
                'input' => $this->input,
                'query' => $query
            ];
        }
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [
        ];

        if (! $this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
            ];
        }

        return $wrapper;
    }
    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function validate()
    {
    }

    /**
     * Send Email
     *
     * @param void
     *
     * @return void
     */
    public function sendEmail(){
        $this->checkEmail();
        if($this->passes()){
            $mailaddress = $this->input['email'];
            $title = '感谢您选择工作网简历模板';
            $template = 'templateurl';
            $data = [
                'url'=>$this->input['url'],
            ];
            $result = Email::send($mailaddress, $template , $data , $title );
            if($result['status'] !=1){
                $this->status = 400;
                $this->description = $result['msg'];
                $this->accepted = false;
            }
        }
    }

    /**
     * Validate the email
     *
     * @param void
     *
     * @return void
     */
    private function checkEmail()
    {
        $rules = [
            'email'=>'required|email',
        ];
        $messages = [
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('email')) {
                $this->errors->add('email', $messages->first('email'));
            }
        }
    }
}
