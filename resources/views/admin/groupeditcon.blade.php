@extends('admin.layouts')


@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/zTreeStyle.css') }}" />

@endsection


@section('content')
	<div class="page-header">
		<h1>
			用户组管理
		</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-sm-12">
			<h3 class="lighter block green">编辑用户组权限
				<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
					<i class="ace-icon fa fa-reply light-green bigger-130"></i>
				</a>
			</h3>
			<ul id="treeDemo" class="ztree"></ul>
			<button data-last="Finish" class="btn btn-sm btn-info" onclick="dosave()">
				<i class="ace-icon fa fa-check bigger-110"></i>
				确认
			</button>
		</div>
	</div>
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('admin/js/usergroup.js') }}"></script>
	<script src="{{ asset('admin/assets/js/ace-extra.min.js') }}"></script>
	<script src="{{ asset('admin/assets/js/zTree/jquery.ztree.min.js') }}"></script>
	<script>
		groupid = "{{ $id }}";
		$(document).ready(function(){
			getcon(groupid);
		});
	</script>
@endsection



