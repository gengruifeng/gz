// 简历模板
$(function(){
	// 选择项目
	var tabA = $(".resumeTab dd a");
	tabA.on("click",function(){
		
		$(this).closest("dd").find("a").removeClass("active");
		$(this).addClass('active');
	});


	// 自动完成 调用
	var countries = [
	   { value: 'Andorra' },
	   { value: '题库'},
	   { value: '题库'},
	   { value: '题库'},
	   { value: '题库'},
	   { value: '题库'},
	];

	$('#autocomplete').autocomplete({
	    lookup: countries,
	    width:440,
	    orientation:'bottom',
	    onSelect: function (suggestion) {
	        dialogcom_warn('You selected: ' + suggestion.value + ', ' + suggestion.data);
	    }
	});

});

function creatresume(){
	var personal  = $(".colomn-personal").text();
	var education  = $(".colomn-education").text();
	var experice  = $(".colomn-experice").text();
	if(personal == "" || personal == undefined ||education == "" || education == undefined ||experice == "" || experice == undefined){
		dialogcom_warn("简历不完善，请把个人信息、教育背景、个人经历的必填项完善哦~")
	}else{
		location.href = "/resume/select";
	}
}

function resumeselect(){
	$.ajax({
		type:"post",
		url:"/ajax/resume/checkselect",
		async : false,
		data:{_token:$("input[name = '_token']").val()},
		dataType: "json",
		success:function(data) {
			location.href = '/resume/select';
		},error: function(xhr, status, error) {
			if (400 === xhr.status) {
				dialogcom_wrong(xhr.responseJSON.errors[0].message);
			} else {
				dialogcom_warn('简历不完善，请把个人信息、教育背景、个人经历的必填项完善哦~');
			}
		}
	})

}