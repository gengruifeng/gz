@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor-mention.css') }}"/>
@endsection
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<p class="green">{{ $subject }}</p>
			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				评论列表
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
								ID
							</label>
						</th>
						<th>评论内容</th>
						<th>评论者</th>
						<th>评论时间</th>
						<th width="200">操作</th>

					</tr>
					</thead>

					{{ csrf_field() }}
					<tbody id = 'articleTbody'>
					<tr>
						<td colspan="10">
							没有记录！

						</td>
					</tr>

					</tbody>

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

	<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

		<div class="modal-dialog" style="width:880px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue">编辑评论</h5>
				</div>
				<form class="form-horizontal" id="answerFrom" onsubmit="return false" method="post" action="{{ url('admin/ajax/competence/add') }}">
					<br />
					{{ csrf_field() }}
					<div id="notong" class="form-group">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">评论内容：</label>
						<div class="col-xs-12 col-sm-9" style="width:600px;">
							<div class="clearfix">
								<textarea style="height: 150px;" id="reason" name="content" class="form-control"></textarea>
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<input type="hidden" id ="id" name="id" value="">

					<div class="modal-footer center">
						<button onclick="subedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('js/module.js') }}"></script>
	<script src="{{ asset('js/hotkeys.js') }}"></script>
	<script src="{{ asset('js/uploader.js') }}"></script>
	<script src="{{ asset('js/simditor.js') }}"></script>
	<script src="{{ asset('js/simditor-mention.js') }}"></script>
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>

	<script src="{{ asset('admin/js/articlecomment.js') }}"></script>
	<script>
		window.onload =function () {

			$(function () {
				articleid = "{{ $articleid }}";
				token = $('input[name=_token]').val();
				seachList(1);
			})
		}
	</script>
@endsection



