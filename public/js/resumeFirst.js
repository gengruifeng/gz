$(document).ready(function() {
	$('.input-startday').datepicker({
		autoclose: true,
		todayHighlight: true,
		format: 'yyyy-mm-dd',
		language: 'zh-CN',
		defaultDate:'2016-10-26',
	})


	if($('#imgUpload').length > 0){
		var _token =  $("input[name = '_token']").val();
		resumeUpload(_token);
	}

	// 自定义方法 手机或邮箱
	jQuery.validator.addMethod("checkUsername", function (value, element) {
		return this.optional(element) || (/^1[3|4|5|7|8]\d{9}$/.test(value) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value));
	}, "用户名格式不正确");

	// 自定义方法 只邮箱
	jQuery.validator.addMethod("emailUsername", function (value, element) {
		return this.optional(element) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
	}, "邮箱格式不正确");

	// 自定义方法 只手机
	jQuery.validator.addMethod("mobUsername", function (value, element) {
		return this.optional(element) || /^1[3|4|5|7|8]\d{9}$/.test(value);
	}, "手机号格式不正确");

	// 自定义方法 密码
	jQuery.validator.addMethod("passcode", function (value, element) {
		return this.optional(element) || /^[\w_]{6,16}$/.test(value);
	}, "密码为6-16个字符，只能输入英文、数字、‘_’");

	//自定义方法 名号
	jQuery.validator.addMethod("checkDisplayName", function (value, element) {
		return this.optional(element) || /^[a-zA-Z0-9_\u4e00-\u9fa5]{4,24}$/.test(value);
	}, "名号为4-24位字符：支持中文、英文、数字、‘_’");
});
	if($('#resumeOneForm').length > 0){
		$('#resumeOneForm').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,

			rules: {
				display_name:{
					required:true,
				},
				mobUsername: {//只可手机
					required:true,
					mobUsername :"手机号格式不正确",
				},
				email: {//邮箱
					required: true,
					emailUsername :"邮箱格式不正确",
				},
				province: {//省份
					required: true,

				},
				city: {// 城市
					required: true,
				},
				sex: {// 性别
					required: true,
				},
				birthday: {// 性别
					required: true,
				},

			},
			messages: {
				display_name:{
					required:"请输入用户名",
				},
				mobUsername:{//用户名 可邮箱、可手机
					required:"请输入手机号",
				},
				email: {
					required: "请输入邮箱",
				},
				province: {
					required: "请选择省份",
				},
				city:{
					required: "请选择城市",
				},
				sex:{
					required: "请选择性别",
				},
				birthday:{
					required: "出生日期",
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				// var uid = $('#uid').val();
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"/ajax/resume/persons",
					async : false,
					data:data,
					dataType: "json",
					success:function(data) {
						location.href = '/myeducations';
					}
				})
					.fail(function(XMLHttpRequest, textStatus, errorThrown) {
						if(textStatus == 'error'){
							if(XMLHttpRequest.status == 401){
								location.href='/login'
							}
							if(XMLHttpRequest.status == 403){
								dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
								type = false;
							}
							var errors = XMLHttpRequest.responseJSON.errors;
							//message = errors[0].message;
							$.each(errors,function (name,val) {
								dialogcom_wrong(val.message);
							})
						}
					})
			}
		});
	}

function resumeOneForm() {
	$("#status").val("2");
	$("form").submit();
}
// function changeCity(th) {
// 	if($(th).val() == '请选择省份或直辖市'){
// 		$(".gogogrf").hide();
// 	}else{
// 		$(".gogogrf").hide();
// 		$('#city').find('option').eq(0).prop('selected',true);
// 		// $("#city  option:selected").attr('data-text');
// 		val = $("#province  option:selected").attr('data-text');
// 		$(".subcity"+val).show();
// 	}
//
// }
function twoback(loca) {
	window.location.replace(loca);
}

function removeinfo(a) {
	if(a!=0){
		$(".sec"+a).remove();
	}
}
// 头像上传
// $('.head').hover(function(){
// 	$(this).find('img').attr('src','../images/headhover.png');
// },function(){
// 	$(this).find('img').attr('src','../images/head120X120.png');
// })

// 头像
// $('.head').hover(function(){
// 	$(this).find('.imgshadow').css('display','block')
// },function(){
// 	$(this).find('.imgshadow').css('display','none')
// })



// // 院校名称 选择效果
// $('.inpbtn').on('click',function(){
// 	$(this).addClass('active');
// 	$(this).closest('.sec').find('.slidmsg').show();
// })
// $('.academy-city a').on('click',function(){
// 	$('.academy-city').hide();
// 	$('.academy-name').show();
// })
// $('.academy-name h2').on('click',function(){
// 	$('.academy-city').show();
// 	$('.academy-name').hide();
// })
// $('.academy-name .btm a').on('click',function(){
// 	var _value=$(this).html();
// 	$(this).closest('.sec').find('.inpbtn ').val(_value).removeClass('active');
// 	$('.academy').hide();
// })
// // 职位
// $('.like-inptext').on('click',function(){
// 	$(this).css('border-color','#fa7e65');
// 	$(this).closest('.sec').find('.slidmsg').show();
// })
// $('.panes a').on('click',function(){
// 	$(this).closest('.slidmsg').hide();
// 	// $(this).css('border-color','#eee');
// 	var _value=$(this).html();
// 	$('.like-inptext').html(_value).css('border-color','#eee');
//
//
// })
