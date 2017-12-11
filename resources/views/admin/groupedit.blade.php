@extends('admin.layouts')
@section('content')
	<div class="page-header">
		<h1>
			用户组管理
		</h1>
	</div><!-- /.page-header -->

	<div class="row">
		<div class="col-sm-12">
			<div>
				<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">
						<h3 class="lighter block green">编辑工作组
							<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
								<i class="ace-icon fa fa-reply light-green bigger-130"></i>
							</a>
						</h3>

						<form class="form-horizontal" id="usergroupform" method="post" onsubmit="return false" action="{{ !empty($userData->id)?url('admin/ajax/usergroup/subedit'):url('admin/ajax/usergroup/subadd') }}">
							{{ csrf_field() }}
							<input type="hidden" name="id" value="{{ !empty($userData->id)?$userData->id:'' }}">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">工作组名称:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="name" name="name" value="{{ !empty($userData->name)?$userData->name:'' }}" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="space-2"></div>


							<div class="col-md-offset-3 col-md-9">
								<button onclick="{{ !empty($userData->id)?'subedit()':'subadd()' }}" class="btn btn-info">
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
	 <script src="{{ asset('admin/js/usergroup.js') }}"></script>
@endsection



