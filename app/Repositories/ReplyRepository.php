<?php

namespace App\Repositories;

use Log;
use Validator;
use App\Entity\Answers;

class ReplyRepository extends Repository implements RepositoryInterface
{

    public function contract()
    {

        $this->validator();

        if ($this->passes()) {
            $anwers = new Answers;
            $anwers->uid = $this->input ['uid'];
            $anwers->question_id = $this->input ['question_id'];
            $anwers->detail = $this->input ['detail'];
            $anwers->anonymous = $this->input ['anonymous'];
            $anwers->save();
        }

    }


    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [];

        if (! $this->passes()) {
            $wrapper = [
                'error_id' => $this->status = 400,
                'description' => '参数错误',
                'error_name' => 'Bad Request',
                'errors' => $this->errors->getErrors()
            ];
        }

        return $wrapper;
    }
    public function validator(){

        if ($this->input) {
            $rules = [
                'uid' => 'required|integer',
                'question_id' => 'required|integer',
                'detail' => 'required',
                'anonymous' => 'integer',
            ];

            $message = [
                'uid.required' =>'uid是必须填的',
                'uid.integer' =>'uid参数是整形',
                'question_id.required' =>'问题id是必须填的',
                'question_id.integer' =>'问题id参数是整形',
                'detail.required' =>'回答内容是必须填的',
                'anonymous.integer' =>'是否匿名参数是整形',

            ];
            $validator = Validator::make($this->input, $rules);

            if ($validator->fails()) {
                $this->status = 400;

                $messages = $validator->errors();

                if ($messages->has('uid')) {
                    $this->errors->add('uid', $messages->first('uid'));
                }

                if ($messages->has('question_id')) {
                    $this->errors->add('question_id', $messages->first('question_id'));
                }

                if ($messages->has('detail')) {
                    $this->errors->add('detail', $messages->first('detail'));
                }

                if ($messages->has('anonymous')) {
                    $this->errors->add('anonymous', $messages->first('anonymous'));
                }

            }

        }

    }


}
