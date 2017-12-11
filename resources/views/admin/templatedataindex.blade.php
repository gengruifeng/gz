@extends('admin.layouts')
@section('stylesheets')
@endsection
@section('content')
	<!-- /section:settings.box -->


	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="tabbable">
				<!-- #section:pages/faq -->
				<ul id="myTab" class="nav nav-tabs padding-18 tab-size-bigger">
					<li class="">
						<a href="#faq-tab-1" onclick="javascript:location.hash='faq-tab-1'" data-toggle="tab">
							<i class="blue ace-icon fa fa-laptop bigger-120"></i>
							求职意向数据
						</a>
					</li>

					<li class="">
						<a href="#faq-tab-2" onclick="javascript:location.hash='faq-tab-2'" data-toggle="tab">
							<i class="green ace-icon fa fa-home bigger-120"></i>
							城市数据
						</a>
					</li>

					<li class="">
						<a href="#faq-tab-3" onclick="javascript:location.hash='faq-tab-3'" data-toggle="tab">
							<i class="orange ace-icon fa fa-credit-card bigger-120"></i>
							专业数据
						</a>
					</li>

					<li class="">
						<a href="#faq-tab-4" onclick="javascript:location.hash='faq-tab-4'" data-toggle="tab">
							<i class="orange ace-icon glyphicon glyphicon-lock  bigger-120"></i>
							职位数据
						</a>
					</li>

					<li class="">
						<a href="#faq-tab-5" onclick="javascript:location.hash='faq-tab-5'" data-toggle="tab">
							<i class="orange ace-icon fa fa-bookmark bigger-120"></i>
							证书数据
						</a>
					</li>

					<li class="">
						<a href="#faq-tab-6" onclick="javascript:location.hash='faq-tab-6'" data-toggle="tab">
							<i class="orange ace-icon glyphicon glyphicon-book  bigger-120"></i>
							学院数据
						</a>
					</li>
				</ul>

				<!-- /section:pages/faq -->
				<div class="tab-content no-border padding-24">
					<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="blue">添加求职意向</h5>
								</div>
								<form class="form-horizontal" action="{{ url('/admin/ajax/templatedata/professionsadd') }}" id="professionseditfrom" onsubmit="return false" method="post">
									<br />
									{{ csrf_field() }}
									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="title" name="title" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<input type="hidden" id ="id" name="id" value="">
									<div class="modal-footer center">
										<button onclick="sutengsubedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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

					<div id="pub_edit_bootbox_major" class="modal fade" tabindex="-1">

						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="blue">添加专业</h5>
								</div>
								<form class="form-horizontal" action="{{ url('/admin/ajax/templatedata/majoradd') }}" id="majoreditfrom" onsubmit="return false" method="post">
									<br />
									{{ csrf_field() }}
									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名称：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="name" name="name" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<input type="hidden" id ="majorid" name="id" value="">
									<div class="modal-footer center">
										<button onclick="majorsubedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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

					<div id="pub_edit_bootbox_position" class="modal fade" tabindex="-1">

						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="blue" id="positionaddblue">添加职位</h5>
								</div>
								<form class="form-horizontal" action="{{ url('/admin/ajax/templatedata/positionadd') }}" id="positioneditfrom" onsubmit="return false" method="post">
									<br />
									{{ csrf_field() }}
									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="positionaddname" name="name" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">一级分类：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select onchange="selectchages(this)" id="positionselects" name="pid">
													<option value="0">作为一级分类</option>

													@if(!empty($selectposition))
														@foreach($selectposition as $k =>$v)
															<option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
														@endforeach
													@endif

												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="erselect" style="display: none" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">二级分类：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												@if(!empty($selectposition))
													@foreach($selectposition as $k =>$v)
													<select class="adderselect" style="display: none" disabled="disabled" id="subpositionselects{{ $v['id'] }}" name="pidtwo">
														<option value="0">作为二级分类</option>
														@if(!empty($v['sub']))
															@foreach($v['sub'] as $kk => $vv)
																<option value="{{ $vv['id'] }}">{{ $vv['name'] }}</option>
															@endforeach
														@endif
													</select>
												@endforeach
												@endif

											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="positionaddorder" name="order" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<input type="hidden" id ="positionid" name="id" value="">
									<div class="modal-footer center">
										<button onclick="positionsubedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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

					<div id="pub_edit_bootbox_certificate" class="modal fade" tabindex="-1">

						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="blue" id="certificateaddblue">添加证书</h5>
								</div>
								<form class="form-horizontal" action="{{ url('/admin/ajax/templatedata/certificateadd') }}" id="certificateeditfrom" onsubmit="return false" method="post">
									<br />
									{{ csrf_field() }}
									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="certificateaddname" name="name" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">分类：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select id="certificateselects" name="pid">
													<option value="0">作为分类</option>

													@if(!empty($selectcertificate))
														@foreach($selectcertificate as $k =>$v)
															<option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
														@endforeach
													@endif

												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="certificateaddorder" name="order" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<input type="hidden" id ="certificateid" name="id" value="">
									<div class="modal-footer center">
										<button onclick="certificatesubedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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

					<div id="pub_edit_bootbox_city" class="modal fade" tabindex="-1">

						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="blue" id="cityaddblue">添加城市</h5>
								</div>
								<form class="form-horizontal" action="{{ url('/admin/ajax/templatedata/cityadd') }}" id="cityeditfrom" onsubmit="return false" method="post">
									<br />
									{{ csrf_field() }}
									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="cityaddname" name="areaname" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">一级分类：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select onchange="selectchagescity(this)" id="cityselects" name="parentid">
													<option value="0">作为一级分类</option>

													@if(!empty($selectcity))
														@foreach($selectcity as $k =>$v)
															<option value="{{ $v['id'] }}">{{ $v['areaname'] }}</option>
														@endforeach
													@endif

												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="erselectcity" style="display: none" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">二级分类：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												@if(!empty($selectcity))
													@foreach($selectcity as $k =>$v)
														<select class="adderselect" style="display: none" disabled="disabled" id="subcityselectscity{{ $v['id'] }}" name="parentidtwo">
															<option value="0">作为二级分类</option>
															@if(!empty($v['sub']))
																@foreach($v['sub'] as $kk => $vv)
																	<option value="{{ $vv['id'] }}">{{ $vv['areaname'] }}</option>
																@endforeach
															@endif
														</select>
													@endforeach
												@endif

											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="cityaddorder" name="sort" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<input type="hidden" id ="cityid" name="id" value="">
									<div class="modal-footer center">
										<button onclick="citysubedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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

					<div id="pub_edit_bootbox_school" class="modal fade" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="blue" id="schooladdblue">添加院校</h5>
								</div>
								<form class="form-horizontal" action="{{ url('/admin/ajax/templatedata/schooladd') }}" id="schooleditfrom" onsubmit="return false" method="post">
									<br />
									{{ csrf_field() }}
									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="schooladdname" name="name" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">城市：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select id="schoolselects" name="cityid">
													<option value="0">请选择</option>
													@if(!empty($selectcity))
														@foreach($selectcity as $k =>$v)
															<option value="{{ $v['id'] }}">{{ $v['areaname'] }}</option>
														@endforeach
													@endif

												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div id="notong" class="form-group">

										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="schooladdorder" name="order" value="" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<input type="hidden" id ="schoolid" name="id" value="">
									<div class="modal-footer center">
										<button onclick="schoolsubedit()" id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
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


					<div class="tab-pane fade" id="faq-tab-1">
						<form id ="professionsFrom" action="{{ url('/admin/ajax/templatedata/professionslist') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<label>名称:</label>
							<input type="text" name ="title" style="height:26px" placeholder="名称">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<button onclick="sutengseachList(1)" class=" btn btn-sm btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
							<div class="space-8"></div>
						</form>
						<div class="table-header">
							求职意向列表

							<button type="button" title="添加求职意向" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addprofessions(1)" id="tijiao" style="float:right;">
								<span class="glyphicon  glyphicon-plus"></span>
								添加求职意向
							</button>
						</div>

						<div class="panel-group accordion-style1 accordion-style2" id="faq-list-1">
							<table id="cv_professions" class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>
										<label class="position-relative">
											ID
										</label>
									</th>
									<th>名称</th>
									<th>创建时间</th>
									<th width="200">操作</th>

								</tr>
								</thead>
								<tbody id = 'professionsTbody'>
								<tr>

									<td colspan="10">
										没有记录！
									</td>
								</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-6">
									<div class="dataTables_info" id="sample-table-2_info"><span id ='tatol'>0</span>条记录，共<span id ='tatolPage'>0</span>页，当前页是<span id ='currenpPge'>0</span></div>
								</div>
								<div class="col-xs-6">
									<div class="dataTables_paginate paging_bootstrap">
										<ul class="pagination">
											<li class="prev">
												<a onclick="sutengseachList(1)" href="javascript:void (0)">首页</a>
											</li>
											<li class="prev">
												<a onclick="sutengup()" href="javascript:void (0)">上一页</a>
											</li>
											<li class="prev">
												<a onclick="sutengnext()" href="javascript:void (0)">下一页</a>
											</li>
											<li class="prev">
												<a onclick="sutengseachList(sutengtatolPage)" href="javascript:void (0)">尾页</a>
											</li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="tab-pane fade" id="faq-tab-2">
						<form id ="cityFrom" action="{{ url('/admin/ajax/templatedata/citylist') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<label style="float: left">一级分类:</label>
							<select style="float: left" onchange="selectchagecity(this)" id="cityselect" name="parentid">
								<option value="0">全部城市</option>
								<option value="-1">全部一级分类</option>
								@if(!empty($selectcity))
									@foreach($selectcity as $k =>$v)
										<option value="{{ $v['id'] }}">{{ $v['areaname'] }}</option>
									@endforeach
								@endif

							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

							<label style="float: left">二级分类:</label>
							<select style="float: left" class="subselect" id="subcityselect" name="parentidtwo">
								<option value="0">全部城市</option>
								<option value="-1">全部二级分类</option>
							</select>
							@if(!empty($selectcity))
								@foreach($selectcity as $k =>$v)
									<select class="subselect" style="display: none;float: left" disabled="disabled" id="subcityselect{{ $v['id']}}" name="parentidtwo">
										<option value="0">全部城市</option>
										<option value="-1">全部二级分类</option>
										@if(!empty($v['sub']))
											@foreach($v['sub'] as $kk => $vv)
												<option value="{{ $vv['id'] }}">{{ $vv['areaname'] }}</option>
											@endforeach
										@endif
									</select>
								@endforeach
							@endif


							<label>城市名称:</label>
							<input type="text" placeholder="城市名称" style="height:26px" name="areaname">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


							<button onclick="cityseachList(1)" class=" btn btn-sm btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
							<div class="space-8"></div>
						</form>
						<div class="table-header">
							城市数据列表

							<button type="button" title="添加城市" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addcity(1)" id="tijiao" style="float:right;">
								<span class="glyphicon  glyphicon-plus"></span>
								添加城市
							</button>
						</div>

						<div class="panel-group accordion-style1 accordion-style2" id="faq-list-1">
							<table id="cv_professions" class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>
										<label class="city-relative">
											ID
										</label>
									</th>
									<th>名称</th>
									<th>类型</th>
									<th>排序</th>
									<th>创建时间</th>
									<th width="200">操作</th>

								</tr>
								</thead>
								<tbody id = 'cityTbody'>
								<tr>

									<td colspan="10">
										没有记录！
									</td>
								</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-6">
									<div class="dataTables_info" id="sample-table-2_info"><span id ='citytatol'>0</span>条记录，共<span id ='citytatolPage'>0</span>页，当前页是<span id ='citycurrenpPge'>0</span></div>
								</div>
								<div class="col-xs-6">
									<div class="dataTables_paginate paging_bootstrap">
										<ul class="pagination">
											<li class="prev">
												<a onclick="cityseachList(1)" href="javascript:void (0)">首页</a>
											</li>
											<li class="prev">
												<a onclick="cityup()" href="javascript:void (0)">上一页</a>
											</li>
											<li class="prev">
												<a onclick="citynext()" href="javascript:void (0)">下一页</a>
											</li>
											<li class="prev">
												<a onclick="cityseachList(citytatolPage)" href="javascript:void (0)">尾页</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="faq-tab-3">
						<form id ="majorFrom" action="{{ url('/admin/ajax/templatedata/majorlist') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<label>专业名称:</label>
							<input type="text" name ="name" style="height:26px" placeholder="专业名称">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<button onclick="majorseachList(1)" class=" btn btn-sm btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
							<div class="space-8"></div>
						</form>
						<div class="table-header">
							专业数据列表

							<button type="button" title="添加专业" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addmajor(1)" id="tijiao" style="float:right;">
								<span class="glyphicon  glyphicon-plus"></span>
								添加专业
							</button>
						</div>

						<div class="panel-group accordion-style1 accordion-style2" id="faq-list-1">
							<table id="cv_professions" class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>
										<label class="position-relative">
											ID
										</label>
									</th>
									<th>名称</th>
									<th>创建时间</th>
									<th width="200">操作</th>

								</tr>
								</thead>
								<tbody id = 'majorTbody'>
								<tr>

									<td colspan="10">
										没有记录！
									</td>
								</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-6">
									<div class="dataTables_info" id="sample-table-2_info"><span id ='majortatol'>0</span>条记录，共<span id ='majortatolPage'>0</span>页，当前页是<span id ='majorcurrenpPge'>0</span></div>
								</div>
								<div class="col-xs-6">
									<div class="dataTables_paginate paging_bootstrap">
										<ul class="pagination">
											<li class="prev">
												<a onclick="majorseachList(1)" href="javascript:void (0)">首页</a>
											</li>
											<li class="prev">
												<a onclick="majorup()" href="javascript:void (0)">上一页</a>
											</li>
											<li class="prev">
												<a onclick="majornext()" href="javascript:void (0)">下一页</a>
											</li>
											<li class="prev">
												<a onclick="majorseachList(majortatolPage)" href="javascript:void (0)">尾页</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="faq-tab-4">
						<form id ="positionFrom" action="{{ url('/admin/ajax/templatedata/positionlist') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<label style="float: left">一级分类:</label>
							<select style="float: left" onchange="selectchage(this)" id="positionselect" name="pid">
								<option value="0">全部职位</option>
								<option value="-1">全部一级分类</option>
								@if(!empty($selectposition))
									@foreach($selectposition as $k =>$v)
										<option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
									@endforeach
								@endif

							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

							<label style="float: left">二级分类:</label>
								<select style="float: left" class="subselect" id="subpositionselect" name="pidtwo">
									<option value="0">全部职位</option>
									<option value="-1">全部二级分类</option>
								</select>
									@if(!empty($selectposition))
										@foreach($selectposition as $k =>$v)
										<select class="subselect" style="display: none;float: left" disabled="disabled" id="subpositionselect{{ $v['id']}}" name="pidtwo">
											<option value="0">全部职位</option>
											<option value="-1">全部二级分类</option>
											@if(!empty($v['sub']))
												@foreach($v['sub'] as $kk => $vv)
													<option value="{{ $vv['id'] }}">{{ $vv['name'] }}</option>
												@endforeach
											@endif
										</select>
									@endforeach
									@endif


							<label>职位名称:</label>
							<input type="text" placeholder="职位名称" style="height:26px" name="name">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


							<button onclick="positionseachList(1)" class=" btn btn-sm btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
							<div class="space-8"></div>
						</form>
						<div class="table-header">
							职位数据列表

							<button type="button" title="添加职位" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addposition(1)" id="tijiao" style="float:right;">
								<span class="glyphicon  glyphicon-plus"></span>
								添加职位
							</button>
						</div>

						<div class="panel-group accordion-style1 accordion-style2" id="faq-list-1">
							<table id="cv_professions" class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>
										<label class="position-relative">
											ID
										</label>
									</th>
									<th>名称</th>
									<th>类型</th>
									<th>排序</th>
									<th>创建时间</th>
									<th width="200">操作</th>

								</tr>
								</thead>
								<tbody id = 'positionTbody'>
								<tr>

									<td colspan="10">
										没有记录！
									</td>
								</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-6">
									<div class="dataTables_info" id="sample-table-2_info"><span id ='positiontatol'>0</span>条记录，共<span id ='positiontatolPage'>0</span>页，当前页是<span id ='positioncurrenpPge'>0</span></div>
								</div>
								<div class="col-xs-6">
									<div class="dataTables_paginate paging_bootstrap">
										<ul class="pagination">
											<li class="prev">
												<a onclick="positionseachList(1)" href="javascript:void (0)">首页</a>
											</li>
											<li class="prev">
												<a onclick="positionup()" href="javascript:void (0)">上一页</a>
											</li>
											<li class="prev">
												<a onclick="positionnext()" href="javascript:void (0)">下一页</a>
											</li>
											<li class="prev">
												<a onclick="positionseachList(positiontatolPage)" href="javascript:void (0)">尾页</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="faq-tab-5">
						<form id ="certificateFrom" action="{{ url('/admin/ajax/templatedata/certificatelist') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<label >分类:</label>
							<select id="certificateselect" name="pid">
								<option value="0">全部证书</option>
								<option value="-1">全部分类</option>
								@if(!empty($selectcertificate))
									@foreach($selectcertificate as $k =>$v)
										<option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
									@endforeach
								@endif

							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

							<label>证书名称:</label>
							<input type="text" placeholder="证书名称" style="height:26px" name="name">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


							<button onclick="certificateseachList(1)" class=" btn btn-sm btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
							<div class="space-8"></div>
						</form>
						<div class="table-header">
							证书数据列表

							<button type="button" title="添加证书" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addcertificate(1)" id="tijiao" style="float:right;">
								<span class="glyphicon  glyphicon-plus"></span>
								添加证书
							</button>
						</div>

						<div class="panel-group accordion-style1 accordion-style2" id="faq-list-1">
							<table id="cv_professions" class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>
										<label class="certificate-relative">
											ID
										</label>
									</th>
									<th>名称</th>
									<th>类型</th>
									<th>排序</th>
									<th>创建时间</th>
									<th width="200">操作</th>

								</tr>
								</thead>
								<tbody id = 'certificateTbody'>
								<tr>

									<td colspan="10">
										没有记录！
									</td>
								</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-6">
									<div class="dataTables_info" id="sample-table-2_info"><span id ='certificatetatol'>0</span>条记录，共<span id ='certificatetatolPage'>0</span>页，当前页是<span id ='certificatecurrenpPge'>0</span></div>
								</div>
								<div class="col-xs-6">
									<div class="dataTables_paginate paging_bootstrap">
										<ul class="pagination">
											<li class="prev">
												<a onclick="certificateseachList(1)" href="javascript:void (0)">首页</a>
											</li>
											<li class="prev">
												<a onclick="certificateup()" href="javascript:void (0)">上一页</a>
											</li>
											<li class="prev">
												<a onclick="certificatenext()" href="javascript:void (0)">下一页</a>
											</li>
											<li class="prev">
												<a onclick="certificateseachList(certificatetatolPage)" href="javascript:void (0)">尾页</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="faq-tab-6">
						<form id ="schoolFrom" action="{{ url('/admin/ajax/templatedata/schoollist') }}" method="post" onsubmit="return false">
							{{ csrf_field() }}
							<label >城市:</label>
							<select id="schoolselect" name="cityid">
								<option value="0">全部城市</option>
								@if(!empty($selectcity))
									@foreach($selectcity as $k =>$v)
										<option value="{{ $v['id'] }}">{{ $v['areaname'] }}</option>
									@endforeach
								@endif

							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

							<label>院校名称:</label>
							<input type="text" placeholder="院校名称" style="height:26px" name="name">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


							<button onclick="schoolseachList(1)" class=" btn btn-sm btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
							<div class="space-8"></div>
						</form>
						<div class="table-header">
							院校数据列表

							<button type="button" title="添加院校" class="btn btn-primary btn-sm btn-default btn-sm" onclick="addschool(1)" id="tijiao" style="float:right;">
								<span class="glyphicon  glyphicon-plus"></span>
								添加院校
							</button>
						</div>

						<div class="panel-group accordion-style1 accordion-style2" id="faq-list-1">
							<table id="cv_professions" class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>
										<label class="school-relative">
											ID
										</label>
									</th>
									<th>名称</th>
									<th>城市</th>
									<th>排序</th>
									<th>创建时间</th>
									<th width="200">操作</th>

								</tr>
								</thead>
								<tbody id = 'schoolTbody'>
								<tr>

									<td colspan="10">
										没有记录！
									</td>
								</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-6">
									<div class="dataTables_info" id="sample-table-2_info"><span id ='schooltatol'>0</span>条记录，共<span id ='schooltatolPage'>0</span>页，当前页是<span id ='schoolcurrenpPge'>0</span></div>
								</div>
								<div class="col-xs-6">
									<div class="dataTables_paginate paging_bootstrap">
										<ul class="pagination">
											<li class="prev">
												<a onclick="schoolseachList(1)" href="javascript:void (0)">首页</a>
											</li>
											<li class="prev">
												<a onclick="schoolup()" href="javascript:void (0)">上一页</a>
											</li>
											<li class="prev">
												<a onclick="schoolnext()" href="javascript:void (0)">下一页</a>
											</li>
											<li class="prev">
												<a onclick="schoolseachList(schooltatolPage)" href="javascript:void (0)">尾页</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
@endsection
@section('javascripts')
	{{--暂时这样  总有一天会加databases--}}
		<script src="{{ asset('admin/assets/js/bootbox.min.js') }}"></script>
		<script src="{{ asset('admin/js/dataprofessions.js') }}"></script>
		<script src="{{ asset('admin/js/datamajor.js') }}"></script>
		<script src="{{ asset('admin/js/dataposition.js') }}"></script>
		<script src="{{ asset('admin/js/datacertificate.js') }}"></script>
		<script src="{{ asset('admin/js/datacity.js') }}"></script>
		<script src="{{ asset('admin/js/dataschool.js') }}"></script>
		<script src="{{ asset('admin/js/templatedata.js') }}"></script>

@endsection



