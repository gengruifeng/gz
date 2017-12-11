<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/admin/ajax/account/disableduser',
        '/admin/ajax/competence/getlist',
        '/admin/ajax/competence/getone',
        '/admin/ajax/competence/edit',
        '/admin/ajax/competence/del',
        '/admin/ajax/usergroup/getcon',
        '/admin/ajax/usergroup/saveusercon',
        '/admin/ajax/questions/del',
        '/admin/ajax/articles/check',
        '/admin/ajax/tags/del',
        '/admin/ajax/tags/addcategories',
        '/admin/ajax/tags/delcategories',
        '/ajax/questions/edit',
        '/ajax/questions/ask',
        '/ajax/articles/edit',
        '/ajax/personal/question',
        '/ajax/personal/answer',
        '/ajax/personal/article',
        '/ajax/personal/collect',
        '/ajax/personal/follow',
        '/admin/ajax/questiontool/del',
        '/admin/ajax/answered/del',
        '/admin/ajax/articlecomment/del',
    ];
}
