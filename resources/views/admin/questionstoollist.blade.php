@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" xmlns="http://www.w3.org/1999/html"/>
	<link rel="stylesheet" href="{{ asset('admin/assets/css/chosen.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor-mention.css') }}"/>
@endsection
@section('content')
	<div class="row">
		<div class="col-xs-12">

			<div class="widget-main">
				<button id="dis0" class="btn btn-primary" onclick="addquestions()">添加未发布问题 </button>
				<button id="dis0" class="btn btn-primary" onclick="edituser()">编辑发布用户列表 </button>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="red">*注：每天定时23点执行发布问题脚本，抽取未发布问题200条，平均分配到发布用户列表中每一个用户，问题发布时间为次日的9:00-24:00。</span>
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				未发布问题列表
			</div>
			<!-- <div class="table-responsive"> -->
			<!-- <div class="dataTables_borderWrap"> -->
			<div>

					<table id="tableuser" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<form id ="questionsFrom" action="{{ url('/admin/ajax/questiontool/list') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<thead>
							<tr>
								<th class="center">
									<label class="position-relative">
										<input id="all" class="ace" type="checkbox">
										<span class="lbl"></span>
									</label>
								</th>
								<th>
									<label class="position-relative">
										ID
									</label>
								</th>
								<th>问题标题</th>
								<th>创建者</th>
								<th>创建时间</th>
								<th>操作</th>

							</tr>
							</thead>
					</form>
					<form id ="hotFrom" action="{{ url('/admin/ajax/questions/hot') }}" method="post" onsubmit="return false">

					{{ csrf_field() }}
					<tbody id = 'questionsTbody'>
					<tr>
						<td colspan="10">
							没有记录！

						</td>
					</tr>

					</tbody>

					</form>

				</table>
				<div class="row">
					<div class="col-xs-6">
						<div class="dataTables_info" id="sample-table-2_info"><span id ='tatol'>0</span>条记录，共<span id ='tatolPage'>0</span>页，当前页是<span id ='currenpPge'>0</span></div>
					</div>
					<div class="col-xs-6">
						<div class="dataTables_paginate paging_bootstrap">
							<ul class="pagination">
								<li class="prev">
									<a onclick="seachList(1)" href="javascript:void (0)">首页</a>
								</li>
								<li class="prev">
									<a onclick="up()" href="javascript:void (0)">上一页</a>
								</li>
								<li class="prev">
									<a onclick="next()" href="javascript:void (0)">下一页</a>
								</li>
								<li class="prev">
									<a onclick="seachList(tatolPage)" href="javascript:void (0)">尾页</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>




	<div id="pub_edit_bootbox_questions" class="modal fade" tabindex="-1">
		<div class="modal-dialog" style="width:880px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue" id="questionsblue">添加问题</h5>
				</div>
				<div class="space-2"></div>
				<div class="space-2"></div>
				<div class="space-2"></div>
				<div class="space-2"></div>
				<div class="space-2"></div>

				<form class="form-horizontal" id="questionsadd-form" method="post" onsubmit="return false" action="{{ url('admin/ajax/questiontool/add') }}">
					{{ csrf_field() }}
					<input type="hidden" name="id" value="">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<textarea name="subject" maxlength="50" id="form-field-9" class="form-control limited" style="width: 600px; height: 45px;"></textarea>
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix" style="width:600px">
								<input type="text" id="editor" name="detail" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>

					<!-- #section:plugins/input.chosen -->

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标签:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<select name ="tags[]"  multiple="" id ="tag" class="chosen-select" data-placeholder="请选择标签">
									@if(!empty($tagAll))
										@foreach($tagAll as $k=>$v)
											<option {{ !empty($tag[$v->id]) ?'selected' :'' }} value="{{ $v->id }}">{{ $v->name }}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<div >

					</div>
					<!-- /section:plugins/input.chosen -->

					<div class="modal-footer center">
						<button onclick="questionsaddajax()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
							确定
						</button>
						<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
							取消
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="pub_edit_bootbox_user" class="modal fade" tabindex="-1">
		<div class="modal-dialog" style="width:880px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue" id="questionsblue">编辑发布用户</h5>
				</div>
				<div class="space-12"></div>
				<!---->
				<div id="feed" class="tab-pane active">
					<div class="profile-feed row">
						<div class="col-sm-6">
							<form id ="questionuserFrom" action="{{ url('/admin/ajax/questiontool/user') }}" method="post" onsubmit="return false">
								{{ csrf_field() }}
								<div class="profile-activity clearfix search">
									<div class="col-xs-12 col-sm-8">
										<div class="input-group">
											<input style="width: 300px;" name="display_name" class="form-control search-query" placeholder="发布用户列表(用户名)" type="text">
											<span class="input-group-btn">
												<button type="button" onclick="questionuserseachList(1)" class="btn btn-purple btn-sm">
													Search
													<i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
							</form>


							<div>
								<div class="message-footer clearfix">
									<div class="pull-left">共<span id="questionusertatol">0</span> 条记录 </div>

									<div class="pull-right">
										<div class="inline middle"> 共<span id="questionusertatolPage">0</span>页,当前第<span id="questionusercurrenpPge">0</span>页</div>

										<ul class="pagination middle">
											<li>
												<a title="首页" onclick="questionuserseachList(1)" href="javascript:void(0)">
													<i class="ace-icon fa fa-step-backward  bigger-140 middle"></i>
												</a>
											</li>

											<li>
												<a title="上一页" onclick="questionuserup()" href="javascript:void(0)">
													<i class="ace-icon fa fa-caret-left bigger-140 middle"></i>
												</a>
											</li>


											<li>
												<a title="下一页" onclick="questionusernext()" href="javascript:void(0)">
													<i class="ace-icon fa fa-caret-right bigger-140 middle"></i>
												</a>
											</li>

											<li>
												<a title="尾页" onclick="questionuserseachList(questionusertatolPage)" href="javascript:void(0)">
													<i class="ace-icon fa fa-step-forward bigger-140 middle"></i>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div><!-- /.col -->

						<div class="col-sm-6">
							<form id ="userFrom" action="{{ url('/admin/ajax/questiontool/user') }}" method="post" onsubmit="return false">
								{{ csrf_field() }}
								<div class="profile-activity clearfix search">
									<div class="col-xs-12 col-sm-8">
										<div class="input-group">
											<input style="width: 300px;" class="form-control search-query" name="display_name" placeholder="用户列表(用户名)" type="text">
											<span class="input-group-btn">
												<button onclick="userseachList(1)" type="button" class="btn btn-purple btn-sm">
													Search
													<i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
							</form>

							<div>
								<div class="message-footer clearfix">
									<div class="pull-left">共<span id="usertatol">0</span> 条记录 </div>

									<div class="pull-right">
										<div class="inline middle"> 共<span id="usertatolPage">0</span>页,当前第<span id="usercurrenpPge">0</span>页</div>

										<ul class="pagination middle">
											<li>
												<a title="首页" onclick="userseachList(1)" href="javascript:void(0)">
													<i class="ace-icon fa fa-step-backward  bigger-140 middle"></i>
												</a>
											</li>

											<li>
												<a title="上一页" onclick="userup()" href="javascript:void(0)">
													<i class="ace-icon fa fa-caret-left bigger-140 middle"></i>
												</a>
											</li>


											<li>
												<a title="下一页" onclick="usernext()" href="javascript:void(0)">
													<i class="ace-icon fa fa-caret-right bigger-140 middle"></i>
												</a>
											</li>

											<li>
												<a title="尾页" onclick="userseachList(usertatolPage)" href="javascript:void(0)">
													<i class="ace-icon fa fa-step-forward bigger-140 middle"></i>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div><!-- /.col -->
					</div><!-- /.row -->

					<div class="space-12"></div>

				</div>
				<!---->
				<div class="modal-footer center">

					<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
						取消
					</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('admin/assets/js/jquery.inputlimiter.1.3.1.min.js') }}"></script>
	<script src="{{ asset('admin/assets/js/chosen.jquery.min.js') }}"></script>
	<script src="{{ asset('js/module.js') }}"></script>
	<script src="{{ asset('js/hotkeys.js') }}"></script>
	<script src="{{ asset('js/uploader.js') }}"></script>
	<script src="{{ asset('js/simditor.js') }}"></script>
	<script src="{{ asset('js/simditor-mention.js') }}"></script>

	<script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>

	<script src="{{ asset('admin/js/questiontool.js') }}"></script>
	<script src="{{ asset('admin/js/questionsuser.js') }}"></script>
	<script>
		window.onload =function () {

			$(function () {
				$('.input-sm').datepicker({
					autoclose: true,
					todayHighlight: true,
					format: 'yyyy-mm-dd'
				})
				//show datepicker when clicking on the icon
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
				});


				seachList(1);

				$(document).on('click', 'th input:checkbox' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
						.each(function(){
							this.checked = that.checked;
							$(this).closest('tr').toggleClass('selected');
						});

				});


				$('textarea.limited').inputlimiter({
					remText: '%n character%s remaining...',
					limitText: 'max allowed : %n.'
				});

				$('.chosen-select').chosen({
					allow_single_deselect:true,
					no_results_text:"没有找到",
					max_selected_options:5
				});
				//resize the chosen on window resize
				$(window).on('resize.chosen', function() {
					$('.chosen-select').next().css({'width':300});
				}).trigger('resize.chosen');

				var token = $("input[name = '_token']").val();

				var imditor = new Simditor({
					textarea: $('#editor'),
					placeholder: '请输入...',
					defaultImage: '',
					upload: {
						url: '/ajax/questions/askupload',
						params: {_token:token},
						fileKey: 'file',
						connectionCount: 3,
						leaveConfirm: '正在上传图片，您确定要终止吗？'
					},
					tabIndent: true,
					toolbar: [
						'title',
						'bold',
						'italic',
						'underline',
						'image',
					],
					toolbarFloat: false,
					toolbarFloatOffset: 0,
					toolbarHidden: false,
					pasteImage: true,
					cleanPaste: true,
				});



			})
		}
	</script>
@endsection



