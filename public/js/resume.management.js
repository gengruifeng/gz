$(document).ready(function(){
	$(".jqzoom").imagezoom();

});

//删除简历
$('.resume-alert-del .btnOk').on('click',function(){
	var id = $(this).attr('data-text');
	if(parseInt(id) > 0 ){
		var token = $("input[name = '_token']").val();
		$.ajax({
			type:"post",
			url:"/ajax/resumemanage/resumedelete",
			data:{id:id,_token:token},
			dataType: "json"
		}).always(function(XMLHttpRequest, textStatus) {
			if(XMLHttpRequest.status == 401){
				$('.resume-alert-del').hide();
				dialoglogin();
				return;
			}
			if(XMLHttpRequest.status == 403){
				$('.resume-alert-del').hide();
				dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
				return;
			}
			if(textStatus == 'success'){
				$('.resume-alert-del').hide();
				$('#resumeList'+id).remove();
			}
		});
	}
});

//修改简历标题
$('.resume-alert-mod .btnOk').on('click',function(){
	var _value=$(this).closest('.resume-alert').find('.inptit').val();
	if(_value==""){
		$(this).closest('.resume-alert').find('.inptxt').show();
		return false;
	}else{
		var id = $(this).attr('data-text');
		if(parseInt(id) > 0 ){
			var token = $("input[name = '_token']").val();
			$.ajax({
				type:"post",
				url:"/ajax/resumemanage/resumeupdatetitle",
				data:{id:id,title:_value,_token:token},
				dataType: "json"
			}).always(function(XMLHttpRequest, textStatus) {
				if(XMLHttpRequest.status == 401){
					$('.resume-alert-mod').hide();
					dialoglogin();
					return;
				}
				if(XMLHttpRequest.status == 403){
					$('.resume-alert-mod').hide();
					dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
					return;
				}
				if(textStatus == 'error'){
					var errors = XMLHttpRequest.responseJSON.errors;
					$.each(errors,function (name,vale) {
						$(".resume-alert-mod .btnOk").closest('.resume-alert').find('.inptxt').text(vale);
						$(".resume-alert-mod .btnOk").closest('.resume-alert').find('.inptxt').show();
					})
				}else{
					$("#resumeList"+id).find('h3 a').eq(0).text(_value);
					$('.resume-alert-mod').hide()
				}
			});
		}
	}
});

//下载简历
function resumeDownload(id){
	var token = $("input[name = '_token']").val();
	$.ajax({
		type:"post",
		url:"/ajax/resumemanage/resumedownload",
		data:{_token:token},
		dataType: "json"
	}).always(function(XMLHttpRequest, textStatus) {
		if(XMLHttpRequest.status == 401){
			dialoglogin();
			return;
		}
		if(XMLHttpRequest.status == 403){
			dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
			return;
		}
		if(XMLHttpRequest.status == 200){
		location.href = "/resume/download/"+id
		}
	});
}

