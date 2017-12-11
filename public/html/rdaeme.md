D:\WWW\gz\app\Repositories
==================================
设计相关
颜色：#f87e6a
标签圆角： 10px
按键圆角：6px
==================================================
以下是登录注册相关：

登录页面 自适应 logingIndex

第三方登录账号注册 loginMobile

个人信息 loginPersonal

密码设置成功重新登录 loginRetry

擅长领域 loginExpert

手机号账号注册 loginMobilePssword

手机号注册 2 loginMobileNum

手机设置新密码 loginPssswordResetMob

手机找回密码 loginFindMobile

邮箱认证 2 loginSureEmail

邮箱认证 loginSureEmailSend

邮箱设置新密码 loginPssswordResetEmail

邮箱找回密码 loginFindEmail

邮箱注册 loginRegistrationEmail

账号注册 loginRegistrationEpassword

找回密码邮箱验证 logGoEmail

注册之后直接登陆界面 loginSucessLogin

================================
功能块位置  public/html/page.html如下 ：
分页
名片展示
提示信息 ：
成功：dialogcom_yes
警告：dialogcom_warn
失败：dialogcom_wrong

================================
常用类名 : （常用样式->类名 位置vcommon中）
clearfix 清浮动 fl 左浮动 fr 右浮动
hide 隐藏 show 显示 [如果实现 隐藏 显示 加相关类名即可]
active 选中，当前状态 （写js时注意给当前a button加active类名即可）

===============================
vcommon.css: 引入本样式文件
样式重置表
分页
名片展示
提示信息
确认取消
===================================
新做修改：（已提交git）
提示信息 [jquery-ui.css]
成功：dialogcom_yes 警告：dialogcom_warn 失败：dialogcom_wrong [在page.html中]
样式-验证修改[个人中心-账户安全-手机验证修改页面]
