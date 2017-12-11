@extends('admin.layouts')
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<p>
				<button id ='dis0' class="btn btn-primary" onclick="seachList(1,0)">用户列表 </button>
				<button id ="dis1" class="btn " onclick="seachList(1,1)">封禁用户</button>
			</p>
			<div class="widget-main">
				<form id ="accountFrom" action="{{ url('/admin/ajax/account/list') }}" method="post" onsubmit="return false">
				{{ csrf_field() }}
				<!-- <legend>Form</legend> -->
					<p>
						<label>用户名:</label>

						<input type="text" name ="display_name" style="height:26px" placeholder="用户名">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>邮箱:</label>
						<input type="text" name="email" style="height:26px" placeholder="邮箱">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


						<label>手机号:</label>

						<input type="text" name="mobile" style="height:26px" placeholder="手机号">
					</p>
					<p>
						<label>状&nbsp;&nbsp; 态:</label>&nbsp;&nbsp;
						<label class="middle">
							<input id="id-disable-check" name="occupation[]" value="1" class="ace" type="checkbox">
							<span class="lbl"> 在校学生</span>
						</label>

						<label class="middle">
							<input id="id-disable-check" name="occupation[]" value="2" class="ace" type="checkbox">
							<span class="lbl"> 职场人士</span>
						</label>

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>系统组:</label>&nbsp;&nbsp;
						<label class="middle">
							<input id="id-disable-check" name="group_id[]" value="1" class="ace" type="checkbox">
							<span class="lbl"> 超级管理员</span>
						</label>

						<label class="middle">
							<input id="id-disable-check" name="group_id[]" value="2" class="ace" type="checkbox">
							<span class="lbl"> 前台管理员</span>
						</label>
						<label class="middle">
							<input id="id-disable-check" name="group_id[]" value="0" class="ace" type="checkbox">
							<span class="lbl"> 普通用户</span>
						</label>


						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<label>性别:</label>&nbsp;&nbsp;
						<label class="middle">
							<input id="id-disable-check" name="gender[]" value="1" class="ace" type="checkbox">
							<span class="lbl"> 男</span>
						</label>

						<label class="middle">
							<input id="id-disable-check" name="gender[]" value="2"  class="ace" type="checkbox">
							<span class="lbl"> 女</span>
						</label>
						<label class="middle">
							<input id="id-disable-check" name="gender[]" value="3" class="ace" type="checkbox">
							<span class="lbl"> 保密</span>
						</label>

					</p>
					<p>
						<label>公&nbsp;&nbsp;&nbsp;&nbsp;司:</label>

						<input type="text" style="height:26px" name="company" placeholder="公司">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>学校:</label>
						<input type="text" style="height:26px" name="school" placeholder="学校">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					</p>

					<p>
						<label>职&nbsp;&nbsp;&nbsp;&nbsp;位:</label>

						<input type="text" style="height:26px" name="position" placeholder="职位">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>专业方向:</label>
						<input type="text" style="height:26px" name="department" placeholder="专业方向">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button onclick="seachList(1,vardis)" class="btn btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
					</p>
				</form>
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				用户列表
			</div>
			<!-- <div class="table-responsive"> -->
			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="tableuser" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
					<tr>
						<th class="center">
							<label class="position-relative">
								<input class="ace" type="checkbox">
								<span class="lbl"></span>
							</label>
						</th>
						<th>
							<label class="position-relative">
								<a title="点击排序" href="javascript:void(0)" onclick="orderby('id',this)">ID</a>
							</label>
						</th>
						<th>名号</th>
						<th>邮箱</th>
						<th>手机号</th>
						<th>系统组</th>
						<th><a title="点击排序" href="javascript:void(0)" onclick="orderby('created_at',this)">注册时间</a><span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-down"></i></span></th>
						<th>最后活跃时间</th>
						<th>在线时长</th>
						<th width="200">操作</th>

					</tr>
					</thead>
					<tbody id = 'userTbody'>
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
									<a onclick="seachList(1,vardis)" href="javascript:void (0)">首页</a>
								</li>
								<li class="prev">
									<a onclick="up()" href="javascript:void (0)">上一页</a>
								</li>
								<li class="prev">
									<a onclick="next()" href="javascript:void (0)">下一页</a>
								</li>
								<li class="prev">
									<a onclick="seachList(tatolPage,vardis)" href="javascript:void (0)">尾页</a>
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
	<script src="{{ asset('admin/js/account.js') }}"></script>
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>

@endsection



