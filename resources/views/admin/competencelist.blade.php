@extends('admin.layouts')
@section('content')
	<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="blue">添加权限</h5>
				</div>
				<form class="form-horizontal" id="confrom" onsubmit="return false" method="post" action="{{ url('admin/ajax/competence/add') }}">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">权限名称</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="name" name="name" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>

					<div class="form-group" style="display: none" id ="isurldiv">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">url:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="con" name="con" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>

					<div class="form-group" style="display: none" id ="isurlnamediv">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">url别名:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="url_name" name="url_name" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="order" name="order" value="" class="col-xs-12 col-sm-5" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<div class="form-group" id = "isdefalutdiv" style="display: none">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否为默认页:</label>
							<div class="col-xs-12 col-sm-4">

								<select id="is_default" class="form-control" name="is_default">
									<option value="0" >否</option>
									<option value="1" >是</option>
								</select>
							</div>

					</div>
					<input type="hidden" id ="pid" name="pid" value="">
					<input type="hidden" id ="id" name="id" value="">
					<div class="modal-footer center">
						<button onclick="subcon()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
							提交
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
			<!-- PAGE CONTENT BEGINS -->

			<!-- #section:plugins/fuelux.treeview -->
			<div class="row">
				<div class="col-sm-12">
					<div class="widget-box widget-color-blue2">
						<div class="widget-header">
							<h4  class="widget-title lighter smaller">Choose Categories</h4>
							<button style="float:right;" onclick="addcon(this,0,0)" class="btn btn-primary btn-sm btn-default btn-sm" title="添加权限" type="button">
								<span class="glyphicon  glyphicon-plus"></span>
								添加
							</button>
						</div>

						<div class="widget-body">
							<div class="widget-main padding-8">
								<div class="tree tree-selectable" id="tree1">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- /section:plugins/fuelux.treeview -->
			<script type="text/javascript">
				var $assets = "../assets";//this will be used in fuelux.tree-sampledata.js
			</script>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div>
@endsection
@section('javascripts')
	 {{--暂时这样  总有一天会加databases--}}
	 <script src="{{ asset('admin/assets/js/fuelux/fuelux.tree.min.js') }}"></script>
	 <script src="{{ asset('admin/assets/js/fuelux/data/fuelux.tree-sample-demo-data.js') }}"></script>
	 <script src="{{ asset('admin/assets/js/ace-elements.min.js') }}"></script>
	 <script src="{{ asset('admin/js/competence.js') }}"></script>
	 <script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>

	 <script>
		 window.onload =function () {
			 jQuery(function($){
				 $.ajax({
					 url: '/admin/ajax/competence/getlist',
					 type: 'POST',
					 dataType: 'json',
					 data: {},
				 })
				 .done(function(tt) {
					 succFunction(tt);
				 })
				 .fail(function(XMLHttpRequest, textStatus, errorThrown) {
					 if(textStatus == 'error'){
						 var obj = JSON.parse(XMLHttpRequest.responseText);
						 var errors = obj.errors;
						 $.each(errors,function (name,vale) {
							 pub_alert_error(vale);
						 })
					 }
				 })

				 //组装json
				 function succFunction(tt) {
					 var remoteDateSource = new DataSourceTree({
						 data : tt
					 });
					 //执行  ace_tree
					 $('#tree1').ace_tree({
						 dataSource : remoteDateSource,
						 multiSelect : true,
						 loadingHTML : '<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
						 'open-icon' : 'ace-icon tree-minus',
						 'close-icon' : 'ace-icon tree-plus',
						 'selectable' : false,
						 'selected-icon' : 'ace-icon fa fa-check',
						 'unselected-icon' : 'ace-icon fa fa-times',
						 cacheItems : false,
						 folderSelect : false,
						 gengruifeng : false,
					 });

				 }
			 });

		 }

	 </script>

@endsection


