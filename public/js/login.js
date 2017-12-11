$(document).ready(function(){
	jQuery.validator.addMethod("checkUsername", function(value, element) {
		return this.optional(element) || (/^1[3|4|5|7|8]\d{9}$/.test(value) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value));
	}, "用户名格式不正确。");
	$('#login-form').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			auth_name: {
				required: true,
				checkUsername: true,
			},
			passcode: {
				required: true,
				rangelength:[6,16]
			},
		},

		messages: {
			auth_name:{
				required:"请输入用户名。",
			},
			passcode: {
				required: "请输入密码。",
				rangelength: "密码长度为6-16位。",
			},
		},

		highlight: function (e) {
			$(e).parent().removeClass('correct');
			$(e).parent().addClass('err');

		},

		success: function (e) {
			$(e).prev().addClass('correct');
			$(e).prev().removeClass('err');
			$(e).remove();
		},

		errorPlacement: function (error, element) {
			error.insertAfter(element.parent());
		},

		submitHandler: function (form) {
			var options = {
				success: function() {
					if($('input[name=l]').val() != undefined && $('input[name=l]').val() != ''){
						window.location.href = $('input[name=l]').val()
					}else{
						window.location.href = '/';
					}
				},
				error: dialogerrorHandle
			};
			$(form).ajaxSubmit(options);
		}

	});
});

function dialogerrorHandle(response, status, xhr, form) {
	switch (response.status) {
		case 400:
			dialogerrorPush(response.responseJSON.errors);
			break;

		case 428:
			window.location.href = '/personalinfoadd';
			break;

		default:
			dialogerrorTip(response.responseJSON);
			break;
	}
}


function dialogerrorPush(errors) {
	var l = errors.length;

	for (var i = 0; i < l; i++) {
		$("input[name=" + errors[i].input + "]").parent().addClass('err');
		$("input[name=" + errors[i].input + "]").parent().after('<p id="'+errors[i].input+'-error" class="red-text">'+errors[i].message+'</p>')
		$('.correct').removeClass('correct')
	}
}

function dialogerrorTip(error) {
	$("input[name=auth_name]").parent().addClass('err');
	if( undefined == error.description){
		$("input[name=auth_name]").parent().after('<p id="auth_name-error" class="red-text"">'+error+'</p>')
		$('.correct').removeClass('correct')
	}else{
		$("input[name=auth_name]").parent().after('<p id="auth_name-error" class="red-text"">'+error.description+'</p>')
		$('.correct').removeClass('correct')
	}


}


