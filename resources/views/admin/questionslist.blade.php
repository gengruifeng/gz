@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" />
@endsection
@section('content')
	<div class="row">
		<div class="col-xs-12">

			<div class="widget-main">
				<form id ="questionsFrom" action="{{ url('/admin/ajax/questions/getlist') }}" method="post" onsubmit="return false">
				{{ csrf_field() }}
				<!-- <legend>Form</legend> -->
					<p>
						<label>问题标题:</label>

						<input type="text" name ="subject" style="height:26px" placeholder="问题标题">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>发布时间范围:</label>
						<input class="input-sm" type="text" name="stime" style="height:26px" placeholder="起始时间">
						<label>-</label>
						<input class="input-sm" type="text" name="etime" style="height:26px" placeholder="结束时间">
					</p>

					<p>
						<label>作&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;者:</label>

						<input type="text" style="height:26px" name="display_name" placeholder="作者">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态:</label>
						<select id="form-field-select-1" name ="status" >
							<option value="0">已发布</option>
							<option value="1">已删除</option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>回答数:</label>
						<input type="text" style="height:26px;width: 50px" name="sanswered" placeholder="">
						-
						<input type="text" style="height:26px;width: 50px;" name="eanswered" placeholder="">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>是否热门:</label>
						<label class="middle">
							<input id="id-disable-check" class="ace" type="checkbox" value="1" name="is_hot[]">
							<span class="lbl"> 是</span>
						</label>
						<label class="middle">
							<input id="id-disable-check" class="ace" type="checkbox" value="0" name="is_hot[]">
							<span class="lbl"> 否</span>
						</label>
						&nbsp;&nbsp;&nbsp;
						<button onclick="seachList(1)" class="btn btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>

					</p>

				</form>
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				问题列表
			</div>
			<!-- <div class="table-responsive"> -->
			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="tableuser" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
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
								<a title="点击排序" href="javascript:void(0)" onclick="orderby('id',this)">ID</a>
							</label>
						</th>
						<th>问题标题</th>
						<th><a title="点击排序" href="javascript:void(0)" onclick="orderby('answered',this)">回答</a></th>
						<th><a title="点击排序" href="javascript:void(0)" onclick="orderby('stared',this)">关注</a></th>
						<th><a title="点击排序" href="javascript:void(0)" onclick="orderby('viewed',this)">浏览</a></th>
						<th>作者</th>
						<th><a title="点击排序" href="javascript:void(0)" onclick="orderby('updated_at',this)">发布时间</a><span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-down"></i></span></th>
						<th width="200">操作</th>

					</tr>
					</thead>
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
						<div class="dataTables_info" id="sample-table-2_info">
							<button onclick="recommend(1)" class="btn btn-white btn-default" type="button">推介热门</button>
							<button onclick="recommend(0)" class="btn btn-white btn-default" type="button">取消推介热门</button>
						</div>
						<div class="dataTables_info" id="sample-table-2_info">
							<span id ='tatol'>0</span>条记录，共<span id ='tatolPage'>0</span>页，当前页是<span id ='currenpPge'>0</span>
							,每页
							<select onchange="selectcount(this)" id="pageSize" name="pageSize" class="ui-pg-selbox" role="listbox">
								<option role="option" value="20" selected="selected">20</option>
								<option role="option" value="50">50</option>
								<option role="option" value="100">100</option>
							</select>条记录
						</div>
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
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>

	<script src="{{ asset('admin/js/question.js') }}"></script>
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
			})
		}
	</script>
@endsection



