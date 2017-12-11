@extends('admin.layouts')
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="widget-main">
			</div>

			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				用户列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加工作组" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					添加工作组
				</button>
			</div>
			<!-- <div class="table-responsive"> -->
			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="tablegroup" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
					<tr>

						<th>
							<label class="position-relative">
								ID
							</label>
						</th>
						<th>工作组名称</th>
						<th>创建人</th>
						<th>更新时间</th>
						<th>创建时间</th>
						<th width="200">操作</th>

					</tr>
					</thead>
					<tbody id = 'userTbody'>
					@if(!empty($data))
						@foreach($data as $k=>$v)
							<tr>
								<td>
									{{ $v->id }}
								</td>
								<td>{{ $v->name }}</td>
								<td>{{ $v->admin_id }}</td>
								<td>{{ $v->created_at }}</td>
								<td>{{ $v->updated_at }}</td>
								<td>
									<button onclick="edit({{ $v->id }})" class="btn btn-xs btn-success">
										编辑
									</button>
									<button onclick="editcon({{ $v->id }})" class="btn btn-xs btn-success">
										编辑权限
									</button>
									<button class="btn btn-xs btn-success">
										删除
									</button>
								</td>
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>

		</div>
	</div>
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('admin/js/usergroup.js') }}"></script>

@endsection



