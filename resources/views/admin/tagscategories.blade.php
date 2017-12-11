@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/chosen.css') }}" />
@endsection
@section('content')
	<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue">添加领域</h5>
				</div>
				<form class="form-horizontal" id="edittagsfrom" onsubmit="return false" method="post">
					<br />
					{{ csrf_field() }}
					<div id="notong" class="form-group">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">领域名称：</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="entity" name="entity" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>

					<div id="notong" class="form-group">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序：</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="order" name="order" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
                    <div class="space-2"></div>
{{--第一个上传 图--}}
					<div id="notong" class="form-group clearfix">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right fl" for="name" style="width: 120px;height: 30px;background: gold;overflow: hidden;margin-left:35px;position: relative;line-height: 30px;text-align: center;padding: 0;border-radius: 6px;color:#fff;">图标(选中前)
							<input style="width: 100%;height: 100%;overflow: hidden;opacity: 0;position: absolute;left: 0;top: 0;;" type="file" id="categoryUpload" name="categoryUpload" draggable="true"  value="上传1" single/>
						</label>
						<div class="col-xs-12 col-sm-9 fl">
							<div class="clearfix">
								<div id="categoryImg" style="border:1px solid #d5d5d5; width: 60px; height: 60px; "></div>
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					{{--第二个上传 图--}}
					<div id="notong" class="form-group clearfix">

						<label class="control-label col-xs-12 col-sm-3 no-padding-right fl" for="name" style="width: 120px;height: 30px;background: gold;overflow: hidden;margin-left:35px;position: relative;line-height: 30px;text-align: center;padding: 0;border-radius: 6px;color:#fff;">图标(选中后)
							<input style="width: 100%;height: 100%;overflow: hidden;opacity: 0;position: absolute;left: 0;top: 0;;" type="file" id="categoryUploadHide" name="categoryUploadHide" draggable="true"  value="上传1" single/>
						</label>
						<div class="col-xs-12 col-sm-9 fl">
							<div class="clearfix">
								<div id="categoryImgHide" style="border:1px solid #d5d5d5; width: 60px; height: 60px; "></div>
							</div>
						</div>
					</div>
					<div class="space-2"></div>

					<input type="hidden" id ="categoryurl" name="categoryurl" value="">
					<input type="hidden" id ="categoryurlhide" name="categoryurlhide" value="">
					<input type="hidden" id ="id" name="id" value="">
					<div class="modal-footer center">
						<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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
				<a id ='dis0' href="{{ url('admin/tags/list') }}" class="btn" >标签列表 </a>
				<a id ="dis1" href="{{ url('admin/tags/categories') }}" class="btn  btn-primary">擅长领域</a>
			</p>


			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->
				<div class="space-6"></div>

				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<!-- #section:pages/invoice -->
						<div class="widget-box transparent">
							<div class="widget-header widget-header-large">
								<h3 class="widget-title grey lighter">
									<i class="ace-icon fa fa-leaf green"></i>
									请选择
								</h3>
								<button type="button" title="添加领域" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addtags(1)" id="tijiao" style="float:right;">
									<span class="glyphicon  glyphicon-plus"></span>
									添加领域
								</button>
							</div>

							<div class="widget-body">
								<div class="widget-main padding-24">
									<div class="row">

										@if(!empty($categories))
											@foreach($categories as $k=>$v)
												<div class="well">
													<span class="widget-title grey lighter">{{ $v->entity}}：</span>

													<select data-id="{{ $v->id }}"  multiple="" id ="tag" class="chosen-select" data-placeholder="请选择标签">
														@if(!empty($tagAll))
															@foreach($tagAll as $kk=>$vv)
																<option {{ !empty($categoriesTag[$v->id][$vv->id]) ?'selected' :'' }} value="{{ $vv->id }}">{{ $vv->name }}</option>
															@endforeach
														@endif
													</select>

													<span style="float: right"><a href="javascript:void(0)" onclick="del({{ $v->id }})">删除</a></span>
													<span style="float: right"> | </span>
													<span style="float: right"> <a href="javascript:void(0)" onclick="addtags(2,'{{ $v->id }}','{{ $v->entity }}','{{ $v->order }}','{{ $v->pic }}','{{ "/categories/".$v->pic }}','{{ $v->pic_hide }}','{{ "/categories/".$v->pic_hide}}')">编辑</a></span>
												</div>
											@endforeach
										@endif
									</div>
							</div>
						</div>

						<!-- /section:pages/invoice -->
					</div>
				</div>

				<!-- PAGE CONTENT ENDS -->
			</div>

		</div>
	</div>
    </div>
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>
	<script src="{{ asset('admin/js/tags.js') }}"></script>
	<script src="{{ asset('admin/assets/js/chosen.jquery.min.js') }}"></script>
	<script src="{{ asset('admin/js/categories.js') }}"></script>

    <script src="{{ asset('js/plupload.full.min.js') }}"></script>
    <script src="{{ asset('js/upload.js') }}"></script>
    <script>
		window.onload =function () {

			$(function () {
				$('.chosen-select').chosen({
					allow_single_deselect:true,
					no_results_text:"没有找到"
				});
				//resize the chosen on window resize
				$(window).on('resize.chosen', function() {
					$('.chosen-select').next().css({'width':300});
				}).trigger('resize.chosen');

				$('.chosen-select').on('change', function(e, params) {
					if(params.selected != undefined){
						selectCategories($(this).attr('data-id'),params.selected);
					}else if(params.deselected  != undefined){
						delSelectCategories($(this).attr('data-id'),params.deselected);
					}
				});

			})
		}
	</script>
@endsection



