@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/chosen.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}"/>
@endsection
@section('content')
	<div class="page-header">
		<h1>
			文章管理
		</h1>
	</div><!-- /.page-header -->

	<div class="row">
		<div class="col-sm-12">
			<div>
				<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">
						<h3 class="lighter block green">编辑文章
							<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
								<i class="ace-icon fa fa-reply light-green bigger-130"></i>
							</a>
						</h3>
						<div class="space-6"></div>
						<div class="space-6"></div>

						<div class="space-2"></div>


						<div class="space-6"></div>

						<div class="space-2"></div>
						<form class="form-horizontal" id="validation-form" method="post" onsubmit="return false" action="{{ url('admin/ajax/articles/edit') }}">

							<div class="form-group">
								<div id="72l22" class="form-group control-label col-xs-12">
									@if(!empty($data->thumbnails))
										<img class="col-xs-offset-3" style="float: left;width: 350px;height: 200px;"  id="suteng"  src="{{ $data->thumbnails }}" />
									@endif
								</div>
							</div>
							{{ csrf_field() }}
							<input type="hidden" name="id" value="{{ !empty($data->id)?$data->id :''}}">

							<div class="form-group">

								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">设置缩略图:</label>


								<div class="col-xs-12 col-sm-9">
									<div class="clearfix" style="position: relative;">
										<p>
											<button class="btn btn-sm btn-primary btn-white btn-round" style="float: left;position: absolute;display: inline-block;width: 100px;height:25px;z-index: 0;" href="javascirpt:void(0)">


												选择图片
											</button>
											<input style="opacity:0;position: relative;z-index: 100;display: inline-block;width: 100px;margin-right: 20px;" type="file" id="imgUpload" name="imgUpload" draggable="true" single/>

											<button id="wodeche" class="btn btn-sm btn-primary btn-white btn-round" style="float: left;position: absolute;display: inline-block;width: 100px;height:25px;z-index: 0;" href="javascirpt:void(0)">


												upload
											</button>
										</p>

									</div>
								</div>
							</div>
							<div class="space-2"></div>

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<textarea name="subject" maxlength="50" id="form-field-9" class="form-control limited" style="width: 354px; height: 75px;">{{ $data->subject }}</textarea>
									</div>
								</div>
							</div>
							<div class="space-2"></div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>

								<div style="overflow: hidden" class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<textarea style="overflow: hidden!important;" name="detail" id="editor">{{ $data->detail }}</textarea>
									</div>
								</div>
							</div>
							<div class="space-2"></div>

							<!-- #section:plugins/input.chosen -->

							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标签:</label>

								<div class="col-xs-7 col-sm-9">
									<div class="clearfix">
										<select name ="tag[]"  multiple="" id ="tag" class="chosen-select" data-placeholder="请选择标签">
											@if(!empty($tagAll))
												@foreach($tagAll as $k=>$v)
													<option {{ !empty($tag[$v->id]) ?'selected' :'' }} value="{{ $v->id }}">{{ $v->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
							<div class="space-2"></div>
							<div >

							</div>
							<!-- /section:plugins/input.chosen -->

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
	 <script src="{{ asset('admin/assets/js/jquery.inputlimiter.1.3.1.min.js') }}"></script>
	 <script src="{{ asset('admin/assets/js/chosen.jquery.min.js') }}"></script>
	 <script src="{{ asset('js/plupload.full.min.js') }}"></script>

	 <script src="{{ asset('js/module.js') }}"></script>
	 <script src="{{ asset('js/hotkeys.js') }}"></script>
	 <script src="{{ asset('js/uploader.js') }}"></script>
	 <script src="{{ asset('js/simditor.js') }}"></script>
	 <script src="{{ asset('js/simditor-mention.js') }}"></script>

	 <script>
		 _token = "{{ csrf_token() }}";
		 id = "{{ $data->id }}";
		 window.onload =function () {
			 $(function () {

				 $('textarea.limited').inputlimiter({
					 remText: '%n character%s remaining...',
					 limitText: 'max allowed : %n.'
				 });

				 $('.chosen-select').chosen({
					 allow_single_deselect:true,
					 no_results_text:"没有找到",
					 max_selected_options:5
				 });
				 //resize the chosen on window resize
				 $(window).on('resize.chosen', function() {
					 $('.chosen-select').next().css({'width':300});
				 }).trigger('resize.chosen');

				 articlesUpload(_token,id);

				 var token = $("input[name = '_token']").val();

				 var imditor = new Simditor({
					 textarea: $('#editor'),
					 placeholder: '请输入...',
					 defaultImage: '',
					 upload: {
						 url: '/ajax/questions/askupload',
						 params: {_token:token},
						 fileKey: 'file',
						 connectionCount: 3,
						 leaveConfirm: '正在上传图片，您确定要终止吗？'
					 },
					 tabIndent: true,
					 toolbar: [
						 'title',
						 'bold',
						 'italic',
						 'underline',
						 'image',
					 ],
					 toolbarFloat: false,
					 toolbarFloatOffset: 0,
					 toolbarHidden: false,
					 pasteImage: true,
					 cleanPaste: true,
				 });
			 })
		 }
	 </script>
	 <script src="{{ asset('admin/js/articles.js') }}"></script>

@endsection



