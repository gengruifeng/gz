<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>重置密码-工作网</title>
</head>

<body style="padding: 0;margin: 0;font-family: 微软雅黑;width: 100%;">
<!--重置密码开始-->
<table style="width: 1200px;height: 600px;margin: 0 auto;" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="border-bottom: 1px solid rgb(210,210,210);width: 729px;height: 233px;display: block;margin: 0 auto;padding-top: 106px;padding-bottom: 50px;">
            <p style="text-indent: 27px;font-size: 24px;color: #333333;margin-bottom: 29px;">亲爱的用户，您好！</p>
            <p style="padding-left: 27px;
				font-size: 16px;
				color: #999999;
				margin-bottom: 49px;
				line-height: 36px;">我们已经收到了您的密码重置请求，请在24小时内点击下面的按钮重置密码。</p>
            <a style="display: block;
				width: 300px;
				height: 64px;
				margin: 0 auto;
				background: #fa7d65;
				text-decoration: none;
				border: none;
				line-height: 64px;
				text-align: center;
				color: white;
				border-radius: 6px;
				cursor: pointer;
				font-size:20px;" href="{{ url($url) }}">重置密码</a>
        </td>
    </tr>
    <tr>
        <td style="width: 729px;height: 235px; display: block;margin: 0 auto;padding-top: 25px;">
            <p style="width: 464px;
				line-height: 29px;
				font-size: 16px;
				margin: 0 auto;
				color: #999999;">如果以上按钮无法打开，请复制下面的链接到浏览器地址栏中打开 : <span style="text-decoration: underline;
				color: #666666;">{{url($url)}}</span></p>
        </td>
    </tr>
</table>
<!--重置密码密码结束-->
</body>

</html>