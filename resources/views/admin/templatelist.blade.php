@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" />
@endsection
@section('content')
	<div class="row">
		<div class="col-xs-12">

			<div class="widget-main">
				<form id ="templateFrom" action="{{ url('/admin/ajax/template/list') }}" method="post" onsubmit="return false">
				{{ csrf_field() }}
				<!-- <legend>Form</legend> -->

					<p>
						<label>求职意向:</label>

						<select id="form-field-select-1" name ="profession_id" >
							<option value="-1">全部</option>
							@if(!empty($professions))
								@foreach($professions as $k=>$v)
									<option value="{{ $v->id }}">{{ $v->title }}</option>
								@endforeach
							@endif
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>简历语言:</label>
						<select id="form-field-select-1" name ="language" >
							<option value="-1">全部</option>
							<option value="zh-cn">中文简历模板</option>
							<option value="en-us">英文简历模板</option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>模板风格:</label>
						<select id="form-field-select-1" name ="colorscheme" >
							<option value="-1">全部</option>
							<option value="0">黑白简历模板</option>
							<option value="1">彩色简历模板</option>
						</select>

						&nbsp;&nbsp;&nbsp;
						<button onclick="seachList(1)" class="btn btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>

					</p>

				</form>
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				模板列表
				<button style="float:right;" onclick=" javascript:window.location.href='/admin/template/add'" class="btn btn-primary btn-sm btn-default btn-sm" title="添加模板" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					添加模板
				</button>
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
								<a href="javascript:void(0);" onclick="orderby('id',this)">ID</a>
							</label>
						</th>
						<th>模板名称</th>
						<th><a href="javascript:void(0);" onclick="orderby('downloaded',this)">下载量</a></th>
						<th><a href="javascript:void(0);" onclick="orderby('created_at',this)">创建时间</a><span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-down"></i></span></th>
						<th>操作</th>

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

	<script src="{{ asset('admin/js/template.js') }}"></script>
	<script>
		window.onload =function () {
			$(function () {
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



