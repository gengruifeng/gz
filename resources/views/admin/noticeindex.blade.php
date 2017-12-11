@extends('admin.layouts')
@section('stylesheets')
	<link rel="stylesheet" href="{{ asset('admin/assets/css/chosen.css') }}" />
@endsection
@section('content')
	<div class="row">
		<div class="col-xs-12">

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
									系统通知
								</h3>

							</div>

							<div class="widget-body">
								<div class="widget-main padding-24">
									<div class="row">
										<div>
											<form class="form-horizontal" id="noticeform" method="post" onsubmit="return false" action="{{ url('admin/ajax/notice/send') }}">
												{{ csrf_field() }}
												<textarea name="content" id="form-field-8" class="form-control" placeholder="Default Text"></textarea>
												<div class="space-6"></div>
												<button onclick="sendmsg()" style="float: right " class="btn btn-danger">发&nbsp;&nbsp;&nbsp;&nbsp;送</button>
												</form>
										</div>
										<div style="clear: both" class="space-6"></div>
										<div class="space-6"></div>



										<div>
											@if(!empty($data))
												@foreach($data as $k=>$v)
													<div class="well">
														<p>系统通知<span style="float: right ">{{ $v->created_at }}</span></p>
														<p>{{ $v->content }}</p>
													</div>
												@endforeach
											@endif
										</div>
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
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
	<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>
	<script src="{{ asset('admin/js/notice.js') }}"></script>
	<script src="{{ asset('admin/assets/js/chosen.jquery.min.js') }}"></script>
	<script>
		window.onload =function () {

			$(function () {
				$('.chosen-select').chosen({
					allow_single_deselect:true,
					no_results_text:"没有找到",
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



