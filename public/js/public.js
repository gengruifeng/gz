//成功提示
function dialog_3(message){
	$('#dialog_3').removeClass('display');
	$('#dialog_3_p').text(message);
	setTimeout(function(){
		$('#dialog_3').fadeOut();
	},2000);
}

//添加标签自动完成
function autocomplete_wrap() {
	$(document).on('click', '.ui-menu-item-wrapper', function() {
		var status = null;
		var txt = $(this).text();
		if($('#addTag a').length < 5 && txt.length > 0) {
			for(var i = 0; i < $('#addTag a').length; i++) {
				if($('#addTag a').eq(i).text() == txt) {
					status = true;
				}
			}
			if(status) {
				dialogcom_warn('重复了')
				$("#search_tag").val('');
			} else {
				var str1 = "<a href='javascript:void(0)'>" + txt +
					"<span class='offTag'></span>" +
					"</a>";
				$('#addTag').append(str1);
				$("#search_tag").val('');
			}

		} else if($('#addTag a').length >= 5) {
			dialogcom_warn('最多添加五个标签');
			$("#search_tag").val('');
		}

	});
};
//回答滚动到指定区域
function click_scroll() {
	var scroll_offset = $('#discuss_11').offset();
	$('body,html').animate({
		scrollTop: parseInt(scroll_offset.top),
	});
}
//注册页加载页面底部
$('#footer').load('footer.html#foot');
//设置对象宽高
function showOverlay(id) {
	$(id).height($(window).height());
	$(id).width($(window).width());
};
$(function() {
	//头部发布开始
	$('#release').hover(function() {
		$('#releaseUl').removeClass('dispaly');
	}, function() {
		var timer = setTimeout(function() {
			$('#releaseUl').addClass('dispaly');
		}, 200);
		$('#releaseUl').hover(function() {
			clearTimeout(timer);
			$('#releaseUl').removeClass('dispaly');
		}, function() {
			$('#releaseUl').addClass('dispaly');
		});
	});
	//头部发布结束
});
$(function() {

	//个人中心tab切换开始
	$('#fuc>a').click(function() {
		var index = $(this).index();
		$(this).addClass('onClick').siblings('a').removeClass('onClick');
		$('.mc').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
	});
	//个人中心tab切换结束
});
//粉丝开始
$(function() {
	$('.attention').click(function() {
		var index = $(this).index();
		var txt = $(this).text();
		if(txt == '加关注') {
			$(this).addClass('onClick');
			$(this).text('取消关注')
		} else if(txt == '取消关注') {
			$(this).removeClass('onClick');
			$(this).text('加关注');
		};
	});
});
//粉丝结束
//登录状态开始
$(function() {

	$('.loginStatus_1').hover(function() {
		var index = $(this).index();

		$('.loginStatus_2').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
	}, function() {
		var index = $(this).index();
		var timer = setTimeout(function() {
			$('.loginStatus_2').eq(index).addClass('dispaly');
		}, 200);
		$('.loginStatus_2').hover(function() {
			clearTimeout(timer);
		}, function() {
			$('.loginStatus_2').addClass('dispaly');
		});
	});
});
//登录状态结束
//选择擅长领域开始
$(function() {
	var reg = /onClick/g;
	$('.bg').on('click', function() {
		if(reg.test($(this).attr('class'))) {
			$(this).removeClass('onClick');
			$(this).find('span').removeClass('onClick');
			$(this).find('input').removeAttr('checked');
		} else {
			$(this).addClass('onClick');
			$(this).find('span').addClass('onClick');
			$(this).find('input').attr('checked', 'checked');
		}
	});
	$('#beGood').on('click', function(event) {
		$('.beGood').eq(0).addClass('dispaly');
		$('.beGoodCard').eq(0).removeClass('dispaly');
	});

	$('.bgCard').on('click', function() {
		if(reg.test($(this).attr('class'))) {
			$(this).removeClass('onClick');
			$(this).find('input').removeAttr('checked');
		} else {
			$(this).addClass('onClick');
			$(this).find('input').attr('checked', 'checked');
		}
	});
});
//问答页面最新问题切换开始
$(function() {
	$('.qa').on('click', function() {
		var index = $(this).index();
		$(this).addClass('onClick').siblings().removeClass('onClick');
		$('.qa_ul').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
	});
});
//问答页面最新问题切换结束

//人物名片开始
$(function() {
	$('.personCard').hover(function() {
		var t = $(this).offset().top;
		var l = $(this).offset().left;
		$('#callingCard').css({
			'top': t + 20 + 'px',
			'left': l + 'px',
			'display': 'block'
		});
	}, function() {
		var timer = setTimeout(function() {
			$('#callingCard').css({
				'display': 'none'
			});
		}, 100);
		$('#callingCard').hover(function() {
			clearTimeout(timer);
			$('#callingCard').css({
				'display': 'block'
			});
		}, function() {
			$('#callingCard').css({
				'display': 'none'
			});
		});
	});
});

//人物名片结束
//遮罩层滚动事件开始
$(function() {
	//滚动事件
	$(window).on('resize', function() {
		//私信遮罩层
		showOverlay("#maskLayer");
		//确认删除问题
		showOverlay("#dialog_1");
		//确认删除评论
		showOverlay("#dialog_2");
		//发送成功
		showOverlay("#dialog_3");
		//提问遮罩层
		showOverlay("#maskLayer_1");

	});
	//写私信

});

//遮罩层滚动事件结束
//确认删除问题，评论
$(function() {
	$('#confirm_1_off').on('click', function() {
		$('#dialog_1').fadeToggle();
	});
	$('#confirm_2_off').on('click', function() {
		$('#dialog_1').fadeToggle();
	});
});
//写私信遮罩层开始
$(function() {
	$('#writeMessage').on('click', function() {
		$('#maskLayer').removeClass('dispaly');

	});
	$('#call_oof').on('click', function() {
		$('#maskLayer').addClass('dispaly');
	});
	$('#questionsAt').on('click', function() {
		$('#maskLayer_1').removeClass('dispaly');
	});
	$('#call_oof_1').on('click', function() {
		$('#maskLayer_1').addClass('dispaly');
	});
});
//写私信遮罩层结束
//百度分享代码开始
$(function() {
	$('.share').hover(function() {
		var t = $(this).offset().top;
		var l = $(this).offset().left;
		$('#share').css({
			'top': t - 10 + 'px',
			'left': l + 30 + 'px'
		});
		$('#share').removeClass('dispaly');
	}, function() {
		var timer = setTimeout(function() {
			$('#share').addClass('dispaly');
		}, 100);
		$('#share').hover(function() {
			clearTimeout(timer);
		}, function() {
			$('#share').addClass('dispaly');
		});

	});
});
//百度分享代码结束
//邀请他人回答开始
$(function() {
	$('.inviteAt').on('click', function() {
		var t = $(this).offset().top;
		var l = $(this).offset().left;
		$('#invite').css({
			'top': t + 30 + 'px',
			'left': l + 'px'
		});
		$('#invite').fadeToggle();
	});

	$('#invite').hover(function() {
		$('#invite').fadeIn();

	}, function() {
		$('#invite').fadeOut(500);
	});

});
//邀请他人回答结束
//点击回复显示回复框开始
$(function() {
	$('#reply1').on('click', function() {
		$('#ttt').removeClass('display');
		$('#reply_1').removeClass('display');
	});
	$('#offReply_1').on('click', function() {

		$('#reply_1').addClass('display');
	});
});
//点击回复显示回复框结束

//点击评论显示评论列表开始

function discuss() {
	$('.discusscss').fadeToggle();
}

//点击评论显示评论列表结束
//编辑
function edit(e) {
	var text = $('#edit div').html();
	$('#edit div').addClass('display');
	$(e.target).addClass('display');
	$('#offEdit').removeClass('display');
	var textarea_1 = $("<textarea id='editor_0'></textarea><button style='width:82px;height:32px;text-align:center;line-height:32px;font-size:12px;color:white;background:#f87e6a;float:right;outline:none;border:none;border-radius:6px;'>提交</button>")
	$('#edit').append(textarea_1);
	var editor = new Simditor({
		textarea: $('#editor_0'),
		placeholder: '请输入',
		defaultImage: '../images/error.jpg',
		params: {},
		upload: {
			url: '../images/error.jpg',
			params: null,
			fileKey: 'upload_file',
			connectionCount: 3,
			leaveConfirm: 'Uploading is in progress, are you sure to leave this page?'
		},
		mention: {
			items: [{
				id: 1,
				name: "春雨",
				pinyin: "chunyu",
				abbr: "cy",
				url: "http://www.example.com"
			}, {
				id: 2,
				name: "夏荷",
				pinyin: "xiahe",
				abbr: "xh",
			}, {
				id: 3,
				name: "秋叶",
				pinyin: "qiuye",
				abbr: "qy",
			}, {
				id: 4,
				name: "冬雪",
				pinyin: "dongxue",
				abbr: "dx",
			}, ],
		},
		tabIndent: true,
		toolbar: [
			'title',
			'bold',
			'italic',
			'underline',
			'image',
		],
		toolbarFloat: true,
		toolbarFloatOffset: 0,
		toolbarHidden: false,
		pasteImage: true,
		cleanPaste: true,
	});
	editor.setValue(text);
};
//取消编辑
function offEdit(e) {

	$(e.target).addClass('display');
	$('#edit_1').removeClass('display');
	$('#edit div').removeClass('display');
	var text = $('#edit div').html();
	$('#edit').empty();
	$('#edit').append("<div>" + text + "</div>");
}

function offLiSmall() {
	$('#offLiSmall1').remove();
}

function offLiBig() {
	$('#offLiBig1').remove();
}
//发布评论取消标记开始
$(function() {
	$('#offDiscuss').click(function() {
		$('.discusscss').fadeToggle();

	});
});
//发布评论取消标记结束
//点击取消标签开始
$(function() {
	$('#addTag').on('click', 'span', function() {
		$(this).parent().remove();
	});
});
//私信自动完成开始
$(function() {
	$('#writeKeyUp').on('keyup', function() {
		$('#writeKeyUpAt').fadeIn();
	});
	$('#writeKeyUp').on('blur', function() {
		$('#writeKeyUpAt').fadeOut();
	});
	$('#writeKeyUpAt').hover(function() {
		$('#writeKeyUpAt').fadeIn();
	}, function() {
		$('#writeKeyUpAt').fadeOut();
	});
	$('.writeKeyUpAtList').on('click', function() {
		var txt = $(this).children().eq(1).text();
		$('#writeKeyUp').val(txt);
	})
});
//answers.html滚动加载结束
//编辑器开始
//$(function() {
//	var editor = new Simditor({
//		textarea: $('#simditor_11'),
//		placeholder: '请输入',
//		defaultImage: '../images/error.jpg',
//		params: {},
//		upload: {
//			url: '../images/error.jpg',
//			params: null,
//			fileKey: 'upload_file',
//			connectionCount: 3,
//			leaveConfirm: 'Uploading is in progress, are you sure to leave this page?'
//		},
//		mention: {
//			items: [{
//				id: 1,
//				name: "春雨",
//				pinyin: "chunyu",
//				abbr: "cy",
//				url: "http://www.example.com"
//			}, {
//				id: 2,
//				name: "夏荷",
//				pinyin: "xiahe",
//				abbr: "xh",
//			}, {
//				id: 3,
//				name: "秋叶",
//				pinyin: "qiuye",
//				abbr: "qy",
//			}, {
//				id: 4,
//				name: "冬雪",
//				pinyin: "dongxue",
//				abbr: "dx",
//			}, ],
//		},
//		tabIndent: true,
//		toolbar: false,
//		toolbarFloat: true,
//		toolbarFloatOffset: 0,
//		toolbarHidden: false,
//		pasteImage: true,
//		cleanPaste: true,
//	});
//});
//编辑器结束