@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/chosen.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}"/>
@endsection
@section('content')
	<div class="page-header">
		<h1>
			简历模板
		</h1>
	</div><!-- /.page-header -->

	<div class="row">
		<div class="col-sm-12">
			<div>
				<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">
						<h3 class="lighter block green">{{ !empty($data->id)?'编辑简历模板':'添加简历模板' }}
							<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
								<i class="ace-icon fa fa-reply light-green bigger-130"></i>
							</a>
						</h3>
						<div class="space-6"></div>
						<div class="space-6"></div>

						<div class="space-2"></div>


						<div class="space-6"></div>

						<div class="space-2"></div>
						<form class="form-horizontal" id="validation-form" method="post" onsubmit="return false" action="{{ !empty($data->id)?url('admin/ajax/template/edit'):url('admin/ajax/template/add') }}">
							{{ csrf_field() }}
							<input type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : 0 }}">

							<div class="form-group">

								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">设置预览图:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix" style="position: relative;">
										<p>
											<input id="preview" class="col-xs-12 col-sm-4" type="text" readonly value="{{ !empty($data->preview)?$data->preview:'' }}" name="preview">

											<a class="btn btn-sm btn-primary btn-white btn-round" style="float: left;position: absolute;display: inline-block;width: 68px;height:35px;z-index: 0;" href="javascirpt:void(0)">

												选择图片
											</a>
											<input style="opacity:0;position: relative;z-index: 100;display: inline-block;width: 68px;margin-right: 35px;height:35px;" type="file" id="uppreview" name="uppreview" draggable="true" single/>
										</p>

									</div>
								</div>
							</div>


							<div class="space-2"></div>

							<div class="form-group">

								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">模板文件:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix" style="position: relative;">
										<p>
											<input id="file" class="col-xs-12 col-sm-4" type="text" readonly value="{{ !empty($data->file)?$data->file:'' }}" name="file">


											<a class="btn btn-sm btn-primary btn-white btn-round" style="float: left;position: absolute;display: inline-block;width: 68px;height:35px;z-index: 0;" href="javascirpt:void(0)">

												选择文件
											</a>
											<input style="opacity:0;position: relative;z-index: 100;display: inline-block;width: 68px;margin-right: 35px;height:35px;" type="file" id="upfile" name="upfile" draggable="true" single/>
										</p>

									</div>
								</div>
							</div>


							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="subject" name="subject" value="{{ !empty($data->subject)?$data->subject:'' }}" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="space-2"></div>


							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">求职意向:</label>

								<div class="col-xs-12 col-sm-4">
									<select name="profession_id" class="form-control" id="profession_id">
										@if(!empty($professions))
											@foreach($professions as $k=>$v)
												<option {{ !empty($data->profession_id) && $data->profession_id == $v->id ?'selected="selected':'' }} value="{{ $v->id }}">{{ $v->title }}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="space-2"></div>

							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">语言:</label>

								<div class="col-xs-12 col-sm-4">
									<select name="language" class="form-control" id="language">
										<option {{ !empty($data->language) && $data->language == 'zh-cn' ?'selected="selected':'' }} value="zh-cn">中文简历模板 </option>
										<option {{ !empty($data->language) && $data->language == 'en-us' ?'selected="selected':'' }} value="en-us">英文简历模板 </option>
									</select>
								</div>
							</div>
							<div class="space-2"></div>

							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">颜色主题:</label>

								<div class="col-xs-12 col-sm-4">
									<select name="colorscheme" class="form-control col-xs-12 col-sm-4" id="colorscheme">
										<option {{ !empty($data) && $data->colorscheme == 0 ?'selected="selected':'' }} value="0">黑白简历模板 </option>
										<option {{ !empty($data) && $data->colorscheme == 1 ?'selected="selected':'' }} value="1">彩色简历模板 </option>
									</select>
								</div>
							</div>
							<div class="space-2"></div>

							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">特点:</label>

								<div class="col-xs-12 col-sm-4">
									<textarea class="col-xs-12 col-sm-12" name="feature">{{ !empty($data->feature)?$data->feature:'' }}</textarea>
								</div>
							</div>
							<div class="space-2"></div>


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
	 <script src="{{ asset('admin/assets/js/chosen.jquery.min.js') }}"></script>

	 <script src="{{ asset('js/module.js') }}"></script>
	 <script src="{{ asset('js/hotkeys.js') }}"></script>
	 <script src="{{ asset('js/plupload.full.min.js') }}"></script>
	 <script src="{{ asset('js/uploader.js') }}"></script>
	 <script src="{{ asset('js/uploader.js') }}"></script>
	 <script src="{{ asset('admin/js/template.js') }}"></script>

	 <script>
		 _token = "{{ csrf_token() }}";
		 window.onload =function () {
			 $(function () {
				 upminiaturized(_token);
				 uppreview(_token);
				 upfile(_token);
			 })
		 }
	 </script>

@endsection



