@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" />
@endsection
@section('content')
	<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue">审核文章</h5>
				</div>
				<form class="form-horizontal" id="confrom" onsubmit="return false" method="post" action="{{ url('admin/ajax/competence/add') }}">
					<br />
					{{ csrf_field() }}
					<div id="notong" class="form-group">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">原因：</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<textarea id="reason" style="width: 324px;height: 170px;" placeholder="审核不通过原因" id="form-field-8" class="form-control"></textarea>
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<input type="hidden" id ="id" name="id" value="">
					<input type="hidden" id ="type" name="type" value="">

					<div class="modal-footer center">
						<button onclick="subcheck()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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

	<div class="row">
		<div class="col-xs-12">

			<div class="widget-main">
				<form id ="articlesFrom" action="{{ url('/admin/ajax/articles/getlist') }}" method="post" onsubmit="return false">
				{{ csrf_field() }}
				<!-- <legend>Form</legend> -->
					<p>
						<label>文章标题:</label>

						<input type="text" name ="subject" style="height:26px" placeholder="文章标题">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>发布时间范围:</label>
						<input class="input-sm" type="text" name="stime" style="height:26px" placeholder="起始时间">
						<label>-</label>
						<input class="input-sm" type="text" name="etime" style="height:26px" placeholder="结束时间">
					</p>

					<p>
						<label>作&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;者:</label>

						<input type="text" style="height:26px" name="display_name" placeholder="作者">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态:</label>
						<select id="form-field-select-1" name ="standard" >
							<option value="-1">全部</option>
							<option value="0">审核中</option>
							<option value="1">审核通过</option>
							<option value="2">审核不通过</option>
							<option value="3">已删除</option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>评论数:</label>
						<input type="text" style="height:26px;width: 50px" name="scomments" placeholder="">
						-
						<input type="text" style="height:26px;width: 50px;" name="ecomments" placeholder="">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<button onclick="seachList(1)" class="btn btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>

					</p>

				</form>
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				文章列表
			</div>
			<!-- <div class="table-responsive"> -->
			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="tableuser" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
					<tr>
						<th>
							<label class="position-relative">
								ID
							</label>
						</th>
						<th>文章标题</th>
						<th>评论</th>
						<th>浏览</th>
						<th>作者</th>
						<th>发布时间</th>
						<th>状态</th>

						<th width="320">操作</th>

					</tr>
					</thead>

					{{ csrf_field() }}
					<tbody id = 'questionsTbody'>
					<tr>
						<td colspan="10">
							没有记录！

						</td>
					</tr>

					</tbody>


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
	<script src="{{ asset('admin/js/articles.js') }}"></script>
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
			})
		}
	</script>
@endsection



