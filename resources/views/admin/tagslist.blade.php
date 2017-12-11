@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" />
@endsection
@section('content')
	<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue">添加标签</h5>
				</div>
				<form class="form-horizontal" id="edittagsfrom" onsubmit="return false" method="post">
					<br />
					{{ csrf_field() }}
					<div id="notong" class="form-group">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标签名称：</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="addname" name="name" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<input type="hidden" id ="id" name="id" value="">
					<div class="modal-footer center">
						<button onclick="subaddtags(this)" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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
			<p>
				<a id ='dis0' href="{{ url('admin/tags/list') }}" class="btn btn-primary" >标签列表 </a>
				<a id ="dis1" href="{{ url('admin/tags/categories') }}" class="btn">擅长领域</a>
			</p>
			<div class="widget-main">
				<form id ="tagsFrom" action="{{ url('/admin/ajax/tags/getlist') }}" method="post" onsubmit="return false">
				{{ csrf_field() }}
				<!-- <legend>Form</legend> -->
					<p>
						<label>标签名称:</label>

						<input type="text" name ="name" style="height:26px" placeholder="标签名称">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<button onclick="seachList(1)" class="btn btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
					</p>
				</form>
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				标签列表
				<button style="float:right;" id="tijiao" onclick="addtags(1)" class="btn btn-primary btn-sm btn-default btn-sm" title="添加标签" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					添加标签
				</button>
			</div>
			<!-- <div class="table-responsive"> -->
			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="tableuser" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
					<tr>
						<th>
							<label class="position-relative">
								<a href="javascript:void(0);" onclick="orderby('tagged_answers',this)">ID</a>
							</label>
						</th>
						<th>标签名称</th>
						<th><a href="javascript:void(0);" onclick="orderby('tagged_answers',this)">问题数量</a></th>
						<th><a href="javascript:void(0);" onclick="orderby('tagged_articles',this)">文章数量</a></th>
						<th>创建人</th>
						<th><a href="javascript:void(0);" onclick="orderby('created_at',this)">创建时间</a><span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-down"></i></span></th>

						<th>操作</th>

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
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>
	<script src="{{ asset('admin/js/tags.js') }}"></script>
	<script>
		window.onload =function () {

			$(function () {
				seachList(1);
			})
		}
	</script>
@endsection



