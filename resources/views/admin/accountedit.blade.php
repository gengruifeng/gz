@extends('admin.layouts')
@section('content')
	<div class="page-header">
		<h1>
			用户管理
		</h1>
	</div><!-- /.page-header -->

	<div class="row">
		<div class="col-sm-12">
			<div>
				<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">
						<h3 class="lighter block green">编辑用户
							<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
								<i class="ace-icon fa fa-reply light-green bigger-130"></i>
							</a>
						</h3>

						<form class="form-horizontal" id="validation-form" method="post" onsubmit="return false" action="{{ url('admin/ajax/account/subedit') }}">
							{{ csrf_field() }}
							<input type="hidden" name="id" value="{{$userData->id}}">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">用户名号:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="display_name" name="display_name" value="{{ $userData->display_name }}" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="space-2"></div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">绑定邮箱:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="email" name="email" value="{{ $userData->email }}" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">绑定手机号:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="mobile" name="mobile" value="{{ $userData->mobile }}" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>

							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">密码:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="passcode" name="passcode" value="" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">系统组:</label>

								<div class="col-xs-12 col-sm-4">
									<select id="isdegree" class="form-control" name="group_id">
										<option {{ $userData->group_id == 0 ?'selected = "selected"' :'' }} value="0" >普通用户</option>
										@if(!empty($con))
											@foreach($con as $k=>$v)
												<option {{ $userData->group_id == $v->id ?'selected = "selected"' :'' }} value="{{ $v->id }}" >{{ $v->name }}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">邮箱激活状态:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<label>
											<input type="radio" disabled class="ace" checked="checked">
											<span class="lbl"> 是</span>
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<label>
											<input type="radio" disabled class="ace">
											<span class="lbl"> 否</span>
										</label>
									</div>
								</div>
							</div>


							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">封禁用户:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<label>
											<input type="radio" value="1" {{ $userData->disabled == 1 ?'checked = "checked"' :'' }} class="ace" name="disabled">
											<span class="lbl"> 是</span>
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<label>
											<input type="radio" value="0" {{ $userData->disabled == 0 ?'checked = "checked"' :'' }} class="ace" name="disabled">
											<span class="lbl"> 否</span>
										</label>
									</div>
								</div>
							</div>


							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">性别:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<label>
											<input type="radio" value="1" {{ $userData->gender == 1 ?'checked = "checked"' :'' }} class="ace" name="gender">
											<span class="lbl"> 男</span>
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<label>
											<input type="radio" value="2" class="ace" {{ $userData->gender == 2 ?'checked = "checked"' :'' }} name="gender">
											<span class="lbl"> 女</span>
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<label>
											<input type="radio" value="3" class="ace" {{ $userData->gender == 3 ?'checked = "checked"' :'' }} name="gender">
											<span class="lbl"> 保密</span>
										</label>
									</div>
								</div>
							</div>


							<div class="col-md-offset-3 col-md-9">
								<button onclick="subedit()" class="btn btn-info">
									<i class="ace-icon fa fa-check bigger-110"></i>
									Submit
								</button>
								<button class="btn" type="reset">
									<i class="ace-icon fa fa-undo bigger-110"></i>
									Reset
								</button>
							</div>
						</form>
					</div>
				</div>

				<!-- /section:plugins/fuelux.wizard.container -->
			</div>
		</div>
	</div>
@endsection
@section('javascripts')
	 {{--暂时这样  总有一天会加databases--}}
	 <script src="{{ asset('admin/js/accountedit.js') }}"></script>
@endsection



