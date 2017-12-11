<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index')->middleware(['resession']);

// User Login
Route::get('login', 'UsersController@login')->name('login');

// User Logout
Route::get('logout', 'UsersController@logout')->middleware(['resession']);

//问答下拉加载
Route::get('asklist', 'QuestionsController@asklist');

//问答下拉加载
Route::get('search/list', 'SearchController@searchlist');

//文章下拉加载
Route::get('articles/list', 'ArticlesController@articleslist');

//名片加载
Route::get('users/card/{id}', 'UsersController@card');

// 问答脚本
Route::get('askscript/{type}', 'QuestionsController@askscript');

// 第三方登录
Route::get('auth/{service}', 'AuthController@redirectToProvider');

// 登录回调
Route::get('auth/{service}/callback', 'AuthController@handleProviderCallback');

//完善登录信息
Route::get('authcallback', 'UsersController@authcallback')->name('authcallback');

//第三方登录注册
Route::get('authregister', 'UsersController@authregister')->name('authregister');

// Forgot password by mail
Route::get('forgot/email', 'ForgotController@email');

// Forgot password by mobile
Route::get('forgot/mobile', 'ForgotController@mobile');

// Forgot password send mail Successful
Route::get('forgot/emailsend/email/{email}', 'ForgotController@emailsend');

// Forgot password mobile fill in info
Route::get('forgot/mobilefill/mobile/{mobile}', 'Forgotcontroller@mobilefill');

// Forgot email set password
Route::get('forgot/emailfill/token/{token}', 'ForgotController@emailSetPass');

// Account Settings
Route::get('account/settings', 'AccountController@settings')->middleware(['security'])->name('settings');

// Account Avatar
Route::get('account/avatar', 'AccountController@avatar')->middleware(['security']);

Route::get('showavatar/{with}/{id}', 'AccountController@showavatar');

// Account OAuth
Route::get('account/oauth', 'AccountController@oauth')->middleware(['security']);

// Account Safety
Route::get('account/safety', 'AccountController@safety')->middleware(['security']);

// Account Safety-mobile-passsword
Route::get('account/safetymobile', 'AccountController@safetymobile')->middleware(['security']);

// Account Safety-email-passsword
Route::get('account/safetyemail', 'AccountController@safetyemail')->middleware(['security']);

// Account Safety-setemail-one
Route::get('account/setemailone', 'AccountController@setemailone')->middleware(['security']);

// Account Safety-setemail-two
Route::get('account/setemailtwo/email/{email}', 'AccountController@setemailtwo')->middleware(['security']);

// Account Safety-setemail-three
Route::get('account/setemailthree/token/{token}', 'AccountController@setemailthree')->middleware(['security']);

// Account Update Passcode
Route::get('account/passcode', 'AccountController@passcode')->middleware(['security']);

// Account binding - Mobile
Route::get('account/setmobile', 'AccountController@setmobile')->middleware(['security']);

// Account binding - Mobile-finish
Route::get('account/setmobilefinish', 'AccountController@setmobileFinish')->middleware(['security']);

// Account change - Mobile-one
Route::get('account/changemobileone', 'AccountController@changeMobileOne')->middleware(['security']);

// Account change - Mobile-two
Route::get('account/changemobiletwo', 'AccountController@changeMobileTwo')->middleware(['security']);

// Account change - Mobile-three
Route::get('account/changemobilethree', 'AccountController@changeMobileThree')->middleware(['security']);

// Account Proficiency
Route::get('account/proficiency', 'AccountController@proficiency')->middleware(['security']);

// Account set Proficiency
Route::get('account/setproficiency', 'AccountController@setProficiency')->middleware(['security']);

// Update Passcode By Mobile
Route::get('passcode/mobile', 'PasscodeController@mobile');

// Update Passcode By Email
Route::get('passcode/email', 'PasscodeController@email');

// Update Passcode By Token
Route::get('passcode/token', 'PasscodeController@token');

// Account Add Email
Route::get('email/add', 'EmailController@add');

// Account Add Email Successful
Route::get('email/token', 'EmailController@token');

// Account Change Mobile
Route::get('mobile/change', 'MobileController@change');

// Account Switch to New Mobile
Route::get('mobile/switch', 'MobileController@switch');



// User Follower for Private
Route::get('profile/following', 'ProfileController@following')->middleware(['security']);

// User Follower for Private
Route::get('profile/follower', 'ProfileController@follower')->middleware(['security']);

// User Following for Public
Route::get('profile/{id}/following', 'FollowsController@following')
    ->where(['id' => '\d{1,10}'])->middleware(['resession']);

// User Follower for Public
Route::get('profile/{id}/follower', 'FollowsController@follower')
    ->where(['id' => '\d{1,10}'])->middleware(['resession']);

// 个人中心
Route::get('profile','ProfileController@personalInfo')->middleware(['security']);


// Public Profile
Route::get('profile/{id}', 'ProfileController@otherInfo')
    ->where(['id' => '\d{1,10}'])->middleware(['resession']);

Route::get('profile/articles', 'ArticlesController@myArticles')->middleware(['security']);

//我的文章分页显示
Route::get('profile/articlepage', 'ArticlesController@articlePage')->middleware(['security']);

// Questions Ask
Route::get('questions/ask', 'QuestionsController@ask')->middleware(['security']);

// Questions Detail
Route::get('questions/{id}', 'QuestionsController@detail')
    ->where(['id' => '\d{1,10}'])->name('questions')->middleware(['resession']);

// Questions Update
Route::get('questions/update/{id}', 'QuestionsController@askselect')->middleware(['security']);

// Tagged Question
Route::get('questions/tagged/{tag}', 'TagsController@questions')->middleware(['resession']);

// Tag Search
Route::get('tagged/list/{tag}', 'TagsController@questionslist')->middleware(['resession']);

// Search
Route::get('search', 'SearchController@query')->middleware(['resession']);

// Search
Route::get('ajax/search', 'SearchController@queryajax')->middleware(['resession']);

// Search
Route::get('majors/search', 'MajorsController@query')->middleware(['resession']);

// Search
Route::get('school/search', 'MajorsController@schoolquery')->middleware(['resession']);

// Tag Search
Route::get('tags/search', 'TagsController@query')->middleware(['resession']);




// Article Resource
Route::get('articles', 'ArticlesController@index')->middleware(['resession']);
Route::get('articles/{id}', 'ArticlesController@show')
    ->where(['id' => '\d{1,10}'])
    ->middleware(['resession']);

Route::get('articles/{id}/revise', 'ArticlesController@revise')
    ->where(['id' => '\d{1,10}'])
    ->middleware(['security']);

Route::get('articles/compose', 'ArticlesController@compose')->middleware(['security']);



// Users Search
Route::get('users/search', 'UsersController@query')->middleware(['resession']);

// Users avatar Search
Route::get('users/searchavatar', 'ProfileController@selectUser');

//我的简历
Route::get('resume/my', 'ResumeController@resume')->name('myresume')->middleware(['security']);

//编辑简历
Route::get('reviseresume', 'ResumeController@reviseresume')->name('reviseresume')->middleware(['security']);

//首次进入教育背景
Route::get('myeducations', 'ResumeController@myeducations')->name('myeducations')->middleware(['security']);

//首次进入个人经历
Route::get('myexperiences', 'ResumeController@myexperiences')->middleware(['security']);

//简历个人信息
Route::get('resume/persons', 'ResumeController@persons')->middleware(['security']);

//简历求职意向
Route::get('resume/advices', 'ResumeController@advices')->middleware(['security']);

//简历教育背景
Route::get('resume/educations', 'ResumeController@educations')->middleware(['security']);

//简历个人经历
Route::get('resume/experiences', 'ResumeController@experiences')->middleware(['security']);

//简历技能证书
Route::get('resume/diplomas', 'ResumeController@diplomas')->middleware(['security']);

//简历荣誉奖项
Route::get('resume/honors', 'ResumeController@honors')->middleware(['security']);

//简历个人作品
Route::get('resume/projects', 'ResumeController@projects')->middleware(['security']);

//简历兴趣爱好
Route::get('resume/interests', 'ResumeController@interests')->middleware(['security']);


// 问答首页
Route::get('questions', 'QuestionsController@askindex')->middleware(['resession']);

// Curricula Vitae
Route::get('cv/templates/{template}/download', 'CVController@download')->where(['template' => '\d{1,10}'])
    ->middleware(['security']);

Route::get('cv/templates/search/list', 'CVController@searchlist');
Route::get('cv/templates/search', 'CVController@search');
Route::get('cv/templates/{template}', 'CVController@preview')->where(['template' => '\d{1,10}'])->middleware(['resession']);
Route::get('cv/templates', 'CVController@index')->middleware(['resession']);

Route::get('cv/templates/list', 'CVController@templateslist')->middleware(['resession']);

Route::get('cv/templates/email','CVController@email')->middleware(['resession']);

// Ajax Group
Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
    // User Login
    Route::post('login', 'UsersController@login');

    //获取登陆用户信息
    Route::post('getinfo', 'UsersController@getinfo')->middleware(['security']);

    // Matches The "/ajax/question/ask" URL
    Route::post('questions/ask', 'QuestionsController@ask')->middleware(['security']);

    // 问题列表
    Route::post('questions/asklist', 'QuestionsController@asklist')->middleware(['security']);

    // 回答列表
    Route::post('questions/answerslist', 'QuestionsController@answerslist')->middleware(['resession']);

    // 问答上传图片
    Route::post('questions/askupload', 'QuestionsController@askupload')->middleware(['security']);

    //邀请人搜索信息
    Route::post('questions/search', 'QuestionsController@search')->middleware(['security']);

    // 问答名片
    Route::post('questions/askdel', 'QuestionsController@askdel')->middleware(['security']);

    // 问答名片
    Route::post('questions/card', 'QuestionsController@card')->middleware(['resession']);

    Route::post('questions/askedit', 'QuestionsController@askedit')->middleware(['security']);

    // 问答添加
    Route::post('questions/answers', 'QuestionsController@answers')->middleware(['security']);

    // 邀请人列表
    Route::post('questions/invitations', 'QuestionsController@invitations')->middleware(['security']);

    // 添加邀请人回答
    Route::post('questions/invitationsadd', 'QuestionsController@invitationsadd')->middleware(['security']);

    // 回答删除
    Route::post('questions/answeredel', 'QuestionsController@answeredel')->middleware(['security']);

    // 回答编辑
    Route::post('questions/answereup', 'QuestionsController@answereup')->middleware(['security']);


    // 评论列表
    Route::post('questions/commentedlist', 'QuestionsController@commentedlist')->middleware(['security']);

    // 回复列表
    Route::post('questions/replieslist', 'QuestionsController@replieslist')->middleware(['security']);

    // 评论删除
    Route::post('questions/commenteddel', 'QuestionsController@commenteddel')->middleware(['security']);

    // 问答评论
    Route::post('questions/commented', 'QuestionsController@commented')->middleware(['security']);

    // 点赞
    Route::post('questions/voteup', 'QuestionsController@voteup')->middleware(['security']);

    // 关注
    Route::post('questions/stared', 'QuestionsController@stared')->middleware(['security']);

    // 关注
    Route::post('questions/checkuser', 'QuestionsController@checkuser')->middleware(['security']);

    // 注册
    Route::post('users/register','UsersController@register');

    // 验证名号是否存在
    Route::post('users/checkdisplayname','UsersController@checkDisplayName');

    // 完善登录信息
    Route::post('users/information','UsersController@information');

    // 发送邮件
    Route::post('sendmail', 'SendMailController@dosend');

    // 发送短信
    Route::post('sendsms', 'SendSmsController@doSend');

    // 短信修改密码
    Route::post('mobilepass', 'MobilePasswordController@doUp');

    // 邮箱修改密码
    Route::post('mailpass', 'MailPasswordController@doUp');

    // 手机号绑定
    Route::post('account/bindingmobile', 'AccountController@doBindingMobilre')->middleware(['security']);

    // 擅长领域-提交分类
    Route::post('account/subcategory', 'AccountController@subcategory')->middleware(['security']);

    // 擅长领域-提交用户标签
    Route::post('account/subusertags', 'AccountController@subUserTags')->middleware(['security']);

    // 解绑
    Route::post('account/deloauth', 'AccountController@deloauth')->middleware(['security']);

    // 提交个人信息
    Route::post('account/subuserinfo', 'AccountController@subUserInfo')->middleware(['security']);

    // Account Avatar-upload
    Route::post('account/avatarupload', 'AccountController@avatarupload')->middleware(['security']);

    // Compose Article
    Route::post('articles', 'ArticlesController@compose')->middleware(['security']);

    // Revise Article
    Route::post('articles/{id}', 'ArticlesController@revise')->where(['id' => '\d{1,10}'])->middleware(['security']);

    // Star Article
    Route::post('articles/{id}/star', 'ArticlesController@star')->middleware(['security']);

    // Vote Up Article
    Route::post('articles/{id}/voteup', 'ArticlesController@voteUp')->middleware(['security']);

    // Img Article Upload
    Route::post('articles/articleimg', 'ArticlesController@articleimg')->middleware(['security']);

    // Get Article Comments
    Route::get('articles/{id}/comments', 'ArticlesCommentsController@index')
        ->where(['id' => '[0-9]{1,11}'])
        ->middleware(['resession']);

    // Compose Article Comments
    Route::post('articles/{id}/comments', 'ArticlesCommentsController@compose')->where(['id' => '[0-9]{1,11}'])
        ->middleware(['security']);

    // Destroy Article Comment
    Route::post('articles/{aid}/comments/{cid}/destroy', 'ArticlesCommentsController@destroy')->where(['aid' => '[0-9]{1,11}', 'cid' => '[0-9]{1,11}'])
        ->middleware(['security']);

    //个人中心 加关注
    Route::post('personal/addattention', 'PersonalController@addAttention')->middleware(['security']);

    //个人中心 取消关注
    Route::post('personal/delattention', 'PersonalController@delAttention')->middleware(['security']);
    //私信对话页面发送私信
    Route::post('notice/addprivatemsg', 'NoticeController@addPrivateMsg')->middleware(['security']);
    //私信列表页面发送私信
    Route::post('notice/dialogs', 'NoticeController@addDialog')->middleware(['security']);

    //私信列表页面删除私信
    Route::post('notice/deldialog', 'NoticeController@delDialog')->middleware(['security']);

    //个人中心页面向他提问
    Route::post('personal/askquestion', 'PersonalController@askQuestion')->middleware(['security']);

    //H5简历模板发送url到邮箱
    Route::post('cv/templates/sendemail ', 'CvController@sendEmail')->middleware(['resession']);
    
    //简历上传照片
    Route::post('resume/resumeupload ', 'ResumeController@resumeupload')->middleware(['security']);

    //简历个人信息
    Route::post('resume/persons', 'ResumeController@persons')->middleware(['security']);

    //首次进入教育背景
    Route::post('resume/myeducations', 'ResumeController@myeducations')->middleware(['security']);

    //首次进入添加个人经历
    Route::post('resume/myexperiences', 'ResumeController@myexperiences')->middleware(['security']);
    
    //保存简历
    Route::post('resumemanage/resumesave', 'ResumeManageController@resumeSave')->middleware(['security']);

    //删除简历
    Route::post('resumemanage/resumedelete', 'ResumeManageController@resumeDelete')->middleware(['security']);

    //修改简历标题
    Route::post('resumemanage/resumeupdatetitle', 'ResumeManageController@resumeUpdateTitle')->middleware(['security']);

    //下载简历
    Route::post('resumemanage/resumedownload', 'ResumeManageController@resumeDownload')->middleware(['security']);

    //简历求职意向
    Route::post('resume/advices', 'ResumeController@advices')->middleware(['security']);

    //简历兴趣爱好
    Route::post('resume/interests', 'ResumeController@interests')->middleware(['security']);

    //简历教育背景
    Route::post('resume/educations', 'ResumeController@educations')->middleware(['security']);

    //简历个人经历
    Route::post('resume/experiences', 'ResumeController@experiences')->middleware(['security']);

    //简历技能证书
    Route::post('resume/diplomas', 'ResumeController@diplomas')->middleware(['security']);

    //简历奖项荣誉
    Route::post('resume/honors', 'ResumeController@honors')->middleware(['security']);

    //简历个人作品
    Route::post('resume/projects', 'ResumeController@projects')->middleware(['security']);

    //删除教育背景
    Route::post('resume/educationdel', 'ResumeController@educationdel')->middleware(['security']);

    //删除个人经历
    Route::post('resume/experiencedel', 'ResumeController@experiencedel')->middleware(['security']);

    //删除技能证书
    Route::post('resume/diplomadel', 'ResumeController@diplomadel')->middleware(['security']);

    //删除荣誉奖项
    Route::post('resume/honordel', 'ResumeController@honordel')->middleware(['security']);

    //删除个人作品
    Route::post('resume/projectdel', 'ResumeController@projectdel')->middleware(['security']);

    //简历模版检验
    Route::post('resume/checkselect', 'ResumeController@checkselect')->middleware(['security']);

    //城市查找
    Route::post('city', 'ResumeController@city')->middleware(['security']);

    //个人经历session存储
    Route::post('backexperiences', 'ResumeController@backexperiences')->middleware(['security']);
});

// Admin Group
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    // Matches The "/admin/dashboard" URL
//    Route::get('dashboard', 'DashboardController@analysis');
//
//    //后台登陆页
//    Route::get('login', 'LoginController@login');

    //后台403页面
    Route::get('forbidden', 'LoginController@forbidden')->name('adminforbidden')->middleware(['security']);

    //后台登陆成功页
    Route::get('index', 'LoginController@index')->name('adminindex')->middleware(['security','adminauth']);

    //后台用户列表页
    Route::get('account/list', 'AccountController@userList')->name('accountlist')->middleware(['security','adminauth']);

    //编辑用户
    Route::get('account/edit/id/{id}', 'AccountController@edit')->name('accountedit')->middleware(['security','adminauth']);

    //权限组列表页
    Route::get('competence/list', 'CompetenceController@competenceList')->name('competencelist')->middleware(['security','adminauth']);

    //用户角色组列表
    Route::get('usergroup/list', 'UserGroupController@grouplist')->name('usergrouplist')->middleware(['security','adminauth']);

    //用户角色编辑
    Route::get('usergroup/edit/id/{id}', 'UserGroupController@edit')->name('usergroupedit')->middleware(['security','adminauth']);

    //用户角色添加
    Route::get('usergroup/add', 'UserGroupController@add')->name('usergroupadd')->middleware(['security','adminauth']);

    //用户角色编辑权限
    Route::get('usergroup/editcon/id/{id}', 'UserGroupController@editcon')->name('usergroupeditcon')->middleware(['security','adminauth']);

    //问题列表页
    Route::get('questions/list', 'QuestionsController@asklist')->name('questionslist')->middleware(['security','adminauth']);

    //问题编辑页
    Route::get('questions/edit/id/{id}', 'QuestionsController@edit')->name('questionsedit')->middleware(['security','adminauth']);

    //文章列表页
    Route::get('articles/list', 'ArticlesController@artlist')->name('articleslist')->middleware(['security','adminauth']);

    //文章编辑页
    Route::get('articles/edit/id/{id}', 'ArticlesController@edit')->name('articlesedit')->middleware(['security','adminauth']);

    //文章预览页
    Route::get('articlesview/{id}', 'ArticlesController@show')->where(['id' => '\d{1,10}', 'subject' => '[^\s]+'])->name('articlesview')->middleware(['security','adminauth']);

    //标签列表
    Route::get('tags/list', 'TagsController@taglist')->name('taglist')->middleware(['security','adminauth']);

    //标签 擅长领域
    Route::get('tags/categories', 'TagsController@categories')->name('tagcategories')->middleware(['security','adminauth']);

    //系统通知
    Route::get('notice/index', 'NoticeController@index')->name('noticeindex')->middleware(['security','adminauth']);

    //简历模板列表页
    Route::get('template/list', 'TemplateController@templatelist')->name('templateindex')->middleware(['security','adminauth']);

    //简历模板添加页
    Route::get('template/add', 'TemplateController@add')->name('templateadd')->middleware(['security','adminauth']);

    //简历模板编辑页
    Route::get('template/edit/id/{id}', 'TemplateController@edit')->name('templateedit')->middleware(['security','adminauth']);

    //简历数据维护页
    Route::get('template/data', 'TemplateDataController@index')->name('templatedata')->middleware(['security','adminauth']);

    //邀请码列表页
    Route::get('referralcode/list', 'ReferralCodeController@codelist')->name('referralcodelist')->middleware(['security','adminauth']);

    //问题工具页
    Route::get('questiontool/index', 'QuestionToolController@index')->name('questiontool')->middleware(['security','adminauth']);

    //定时添加问题
    Route::get('questiontool/release/{token}', 'QuestionToolController@release')->name('questionrelease');

    //问题回答列表页
    Route::get('answered/{questionid}', 'QuestionsController@answered')->name('questionanswered')->middleware(['security','adminauth']);

    //文章评论列表页
    Route::get('articlecomment/{articlecommentid}', 'ArticlesController@comment')->name('articlecomment')->middleware(['security','adminauth']);

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {

        //用户列表页
        Route::post('account/list', 'AccountController@userList')->name('ajaxaccountlist')->middleware(['security','adminauth']);

        //封禁用户
        Route::post('account/disableduser', 'AccountController@fengjinUser')->name('ajaxaccountdisableduser')->middleware(['security','adminauth']);

        //提交用户信息
        Route::post('account/subedit', 'AccountController@subedit')->name('ajaxaccountsubedit')->middleware(['security','adminauth']);

        //获列表
        Route::post('competence/getlist', 'CompetenceController@getList')->name('ajaxcompetencegetlist')->middleware(['security','adminauth']);

        //获取一条权限信息
        Route::post('competence/getone', 'CompetenceController@getone')->name('ajaxcompetencegetone')->middleware(['security','adminauth']);

        //添加权限
        Route::post('competence/add', 'CompetenceController@add')->name('ajaxcompetenceadd')->middleware(['security','adminauth']);

        //编辑权限
        Route::post('competence/edit', 'CompetenceController@edit')->name('ajaxcompetenceedit')->middleware(['security','adminauth']);

        //删除权限
        Route::post('competence/del', 'CompetenceController@del')->name('ajaxcompetencedel')->middleware(['security','adminauth']);

        //编辑工作组
        Route::post('usergroup/edit', 'UserGroupController@edit')->name('ajaxgroupedit')->middleware(['security','adminauth']);

        //添加工作组
        Route::post('usergroup/add', 'UserGroupController@add')->name('ajaxgroupadd')->middleware(['security','adminauth']);

        //获取工作组的权限
        Route::post('usergroup/getcon', 'UserGroupController@getcon')->name('ajaxgroupgetcon')->middleware(['security','adminauth']);

        //保存工作组的权限
        Route::post('usergroup/saveusercon', 'UserGroupController@saveUserCon')->name('ajaxgroupdosave')->middleware(['security','adminauth']);

        //获取问题列表
        Route::post('questions/getlist', 'QuestionsController@getList')->name('ajaxquestionsgetlist')->middleware(['security','adminauth']);

        //问题删除按钮
        Route::post('questions/del', 'QuestionsController@del')->name('ajaxquestionsgetdel')->middleware(['security','adminauth']);

        //问题编辑按钮
        Route::post('questions/edit', 'QuestionsController@edit')->name('ajaxquestionsgetedit')->middleware(['security','adminauth']);

        //推介热门问题
        Route::post('questions/hot', 'QuestionsController@hot')->name('ajaxquestionsgethot')->middleware(['security','adminauth']);

        //获取文章列表
        Route::post('articles/getlist', 'ArticlesController@getList')->name('ajaxarticlesgetlist')->middleware(['security','adminauth']);

        //获取文章审核接口
        Route::post('articles/check', 'ArticlesController@check')->name('ajaxarticlesgetcheck')->middleware(['security','adminauth']);

        //文章编辑
        Route::post('articles/edit', 'ArticlesController@edit')->name('ajaxarticlesedit')->middleware(['security','adminauth']);

        //文章缩略图上传
        Route::post('articles/upload', 'ArticlesController@upload')->name('ajaxarticlesupload')->middleware(['security','adminauth']);

        //获取标签列表
        Route::post('tags/getlist', 'TagsController@getList')->name('ajaxtaglist')->middleware(['security','adminauth']);

        //添加标签借接口
        Route::post('tags/add', 'TagsController@add')->name('ajaxtagadd')->middleware(['security','adminauth']);

        //编辑标签接口
        Route::post('tags/edit', 'TagsController@edit')->name('ajaxtagedit')->middleware(['security','adminauth']);

        //删除标签接口
        Route::post('tags/del', 'TagsController@del')->name('ajaxtagdel')->middleware(['security','adminauth']);

        //添加擅长标签接口
        Route::post('tags/addcategories', 'TagsController@addCategories')->name('ajaxtagaddcategories')->middleware(['security','adminauth']);

        //删除擅长标签接口
        Route::post('tags/delcategories', 'TagsController@delCategories')->name('ajaxtagdelcategories')->middleware(['security','adminauth']);

        //添加擅长领域
        Route::post('tags/addcategoriesinfo', 'TagsController@addCategoriesInfo')->name('ajaxaddcategoriesinfo')->middleware(['security','adminauth']);

        //添加擅长领域图标
        Route::post('tags/addcategoriespic', 'TagsController@addCategoriesPic')->name('ajaxaddcategoriespic')->middleware(['security','adminauth']);

        //编辑擅长领域
        Route::post('tags/editcategoriesinfo', 'TagsController@editCategoriesInfo')->name('ajaxeditcategoriesinfo')->middleware(['security','adminauth']);

        //删除擅长领域
        Route::post('tags/delcate', 'TagsController@delCategoriesInfo')->name('ajaxdelcategoriesinfo')->middleware(['security','adminauth']);

        //生成邀请码接口
        Route::post('referralcode/add', 'ReferralCodeController@add')->name('ajaxreferralcodeadd')->middleware(['security','adminauth']);

        //发放邀请码接口
        Route::any('referralcode/issued', 'ReferralCodeController@issued')->name('ajaxreferralcodeissued')->middleware(['security','adminauth']);

        //发系统通知接口
        Route::post('notice/send', 'NoticeController@send')->name('ajaxnoticesend')->middleware(['security','adminauth']);

        //简历模板列表接口
        Route::post('template/list', 'TemplateController@getList')->name('templateajaxindex')->middleware(['security','adminauth']);

        //简历模板删除接口
        Route::post('template/del', 'TemplateController@del')->name('templateajaxdel')->middleware(['security','adminauth']);

        //简历模板上传接口
        Route::post('template/upload', 'TemplateController@upload')->name('templateajaxupload')->middleware(['security']);

        //简历模板添加接口
        Route::post('template/add', 'TemplateController@add')->name('templateajaxadd')->middleware(['security','adminauth']);

        //简历模板编辑接口
        Route::post('template/edit', 'TemplateController@edit')->name('templateajaxedit')->middleware(['security','adminauth']);

        //求职意向列表接口
        Route::post('templatedata/professionslist', 'TemplateDataController@professionsList')->name('professionslist')->middleware(['security','adminauth']);

        //求职意向添加接口
        Route::post('templatedata/professionsadd', 'TemplateDataController@professionsAdd')->name('professionsadd')->middleware(['security','adminauth']);

        //求职意向编辑接口
        Route::post('templatedata/professionsedit', 'TemplateDataController@professionsEdit')->name('professionsedit')->middleware(['security','adminauth']);

        //求职意向删除接口
        Route::post('templatedata/professionsdel', 'TemplateDataController@professionsDel')->name('professionsdel')->middleware(['security','adminauth']);

        //专业列表接口
        Route::post('templatedata/majorlist', 'TemplateDataController@majorList')->name('majorlist')->middleware(['security','adminauth']);

        //专业添加接口
        Route::post('templatedata/majoradd', 'TemplateDataController@majorAdd')->name('majoradd')->middleware(['security','adminauth']);

        //专业编辑接口
        Route::post('templatedata/majoredit', 'TemplateDataController@majorEdit')->name('majoredit')->middleware(['security','adminauth']);

        //专业删除接口
        Route::post('templatedata/majordel', 'TemplateDataController@majorDel')->name('majordel')->middleware(['security','adminauth']);

        //职位列表接口
        Route::post('templatedata/positionlist', 'TemplateDataController@positionList')->name('positionlist')->middleware(['security','adminauth']);

        //职位添加接口
        Route::post('templatedata/positionadd', 'TemplateDataController@positionAdd')->name('positionadd')->middleware(['security','adminauth']);

        //职位编辑接口
        Route::post('templatedata/positionedit', 'TemplateDataController@positionEdit')->name('positionedit')->middleware(['security','adminauth']);

        //职位删除接口
        Route::post('templatedata/positiondel', 'TemplateDataController@positionDel')->name('positiondel')->middleware(['security','adminauth']);

        //证书列表接口
        Route::post('templatedata/certificatelist', 'TemplateDataController@certificateList')->name('certificatelist')->middleware(['security','adminauth']);

        //证书添加接口
        Route::post('templatedata/certificateadd', 'TemplateDataController@certificateAdd')->name('certificateadd')->middleware(['security','adminauth']);

        //证书编辑接口
        Route::post('templatedata/certificateedit', 'TemplateDataController@certificateEdit')->name('certificateedit')->middleware(['security','adminauth']);

        //证书删除接口
        Route::post('templatedata/certificatedel', 'TemplateDataController@certificateDel')->name('certificatedel')->middleware(['security','adminauth']);

        //城市列表接口
        Route::post('templatedata/citylist', 'TemplateDataController@cityList')->name('citylist')->middleware(['security','adminauth']);

        //城市添加接口
        Route::post('templatedata/cityadd', 'TemplateDataController@cityAdd')->name('cityadd')->middleware(['security','adminauth']);

        //城市编辑接口
        Route::post('templatedata/cityedit', 'TemplateDataController@cityEdit')->name('cityedit')->middleware(['security','adminauth']);

        //城市删除接口
        Route::post('templatedata/citydel', 'TemplateDataController@cityDel')->name('citydel')->middleware(['security','adminauth']);

        //院校列表接口
        Route::post('templatedata/schoollist', 'TemplateDataController@schoolList')->name('schoollist')->middleware(['security','adminauth']);

        //院校添加接口
        Route::post('templatedata/schooladd', 'TemplateDataController@schoolAdd')->name('schooladd')->middleware(['security','adminauth']);

        //院校编辑接口
        Route::post('templatedata/schooledit', 'TemplateDataController@schoolEdit')->name('schooledit')->middleware(['security','adminauth']);

        //院校删除接口
        Route::post('templatedata/schooldel', 'TemplateDataController@schoolDel')->name('schooldel')->middleware(['security','adminauth']);

        //待发布问题列表
        Route::post('questiontool/list', 'QuestionToolController@getList')->name('questiontoollist')->middleware(['security','adminauth']);

        //添加待发布问题
        Route::post('questiontool/add', 'QuestionToolController@add')->name('questiontooladd')->middleware(['security','adminauth']);

        //获取一条待发布问题
        Route::post('questiontool/getone', 'QuestionToolController@getone')->name('questiontoolgetone')->middleware(['security','adminauth']);

        //编辑待发布问题
        Route::post('questiontool/edit', 'QuestionToolController@edit')->name('questiontooledit')->middleware(['security','adminauth']);

        //删除待发布问题
        Route::post('questiontool/del', 'QuestionToolController@del')->name('questiontooldel')->middleware(['security','adminauth']);

        //获取用户列表
        Route::post('questiontool/user', 'QuestionToolController@getUserList')->name('questiontooluser')->middleware(['security','adminauth']);

        //移除发布用户
        Route::post('questiontool/userdel', 'QuestionToolController@userDel')->name('questiontooluserdel')->middleware(['security','adminauth']);

        //添加发布用户
        Route::post('questiontool/useradd', 'QuestionToolController@userAdd')->name('questiontooluseradd')->middleware(['security','adminauth']);

        //问题回答列表接口
        Route::post('answered/list', 'QuestionsController@getAnsweredList')->name('questionansweredlist')->middleware(['security','adminauth']);

        //问题回答编辑接口
        Route::post('answered/edit', 'QuestionsController@answeredEdit')->name('questionanswerededit')->middleware(['security','adminauth']);

        //问题回答删除接口
        Route::post('answered/del', 'QuestionsController@answeredDel')->name('questionanswereddel')->middleware(['security','adminauth']);

        //文章评论列表接口
        Route::post('articlecomment/list', 'ArticlesController@getCommentList')->name('articlecommentajaxlist')->middleware(['security','adminauth']);

        //文章评论编辑接口
        Route::post('articlecomment/edit', 'ArticlesController@commentEdit')->name('articlecommentajaxedit')->middleware(['security','adminauth']);

        //文章评论删除接口
        Route::post('articlecomment/del', 'ArticlesController@commentDel')->name('articlecommentajaxdel')->middleware(['security','adminauth']);

    });
});

// 手机号注册页面
Route::get('registermobile', 'UsersController@registerMobile');

// 邮箱注册页面
Route::get('registeremail', 'UsersController@registerEmail');

// 邮箱注册信息补全
Route::get('emailinfoadd','UsersController@emailInfoAdd' )->name('emailinfoadd');

// 手机注册信息密码补全
Route::get('mobileinfoadd', 'UsersController@mobileInfoAdd');

// 注册个人信息补全
Route::get('personalinfoadd', 'UsersController@personalInfoAdd');

// 擅长领域信息补全
Route::get('begoodat', 'UsersController@beGoodAt');

// 重新发送邮件
Route::get('sendemailagain','UsersController@sendEmailAgain' );

// 图形验证码
Route::get('users/code/{tmp}', 'UsersController@code');

//邮箱激活
Route::get('emailactive','UsersController@emailactive');

// 注册时邮件确认页面
Route::get('mailconfirm', 'UsersController@mailConfirm');

//系统通知页

Route::get('notifications/others','ProfileController@systemMsg')->middleware(['security']);

//系统通知分页数据

Route::get('notifications/others/page','ProfileController@systemMsgPage')->middleware(['security']);

//私信页面
Route::get('messages','ProfileController@privateMsg')->middleware(['security']);

//私信页面分页数据
Route::get('messages/page','ProfileController@privateMsgPage')->middleware(['security']);

//私信详情页面
Route::get('messages/detail/{dialogid}','ProfileController@privateLetterDetail')->middleware(['security']);

//文章消息页面
Route::get('notifications/articles','ProfileController@articleMsg')->middleware(['security']);

//文章消息页面分页数据
Route::get('notifications/articles/page','ProfileController@articleMsgPage')->middleware(['security']);

//问答消息页面$this->input['pageSize']
Route::get('notifications/answers','ProfileController@askMsg')->middleware(['security']);

//问答消息分页数据
Route::get('notifications/answers/page','ProfileController@askMsgPage')->middleware(['security']);

//简历管理列表
Route::get('resume/list','ResumeManageController@resumelist')->middleware(['security']);

//简历管理分页列表
Route::get('resume/pagelist','ResumeManageController@resumePageList')->middleware(['security']);

//简历模板页面
Route::get('resume/select','ResumeManageController@resumeSelect')->middleware(['security']);

//选择简历模板
Route::get('resume/choice/{name}','ResumeManageController@resumeChoice')->middleware(['security']);

//下载模板
Route::get('resume/download/{id}','ResumeManageController@download')->where(['id' => '\d{1,10}'])->middleware(['security']);

//个人中心 提问
Route::get('profile/question', 'ProfileController@question')->middleware(['resession']);
//个人中心 回答
Route::get('profile/answer', 'ProfileController@answer')->middleware(['resession']);

//个人中心 文章
Route::get('profile/article', 'ProfileController@article')->middleware(['resession']);

//个人中心 收藏
Route::get('profile/collect', 'ProfileController@collect')->middleware(['resession']);

//个人中心 关注
Route::get('profile/follow', 'ProfileController@follow')->middleware(['resession']);
