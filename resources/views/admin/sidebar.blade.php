
<!-- 左侧导航 -->
	<div id="sidebar" class="sidebar responsive">
		@if(!empty($conTree))
			<ul class="nav nav-list">
				<li class="">
					<a href="index.html">
						<i class="menu-icon fa fa-tachometer"></i>
						<span class="menu-text"> {{ date('Y-m-d',time()) }} </span>
					</a>

					<b class="arrow"></b>
				</li>
				@foreach($conTree as $k => $v)

					<li class="{{ !empty($currentData['module']) && $currentData['module'] == $v['module'] ? 'active open hsub': ''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon
								@if($v['name'] == '用户模块')
									fa fa-user
								@elseif($v['name'] == '内容模块')
									fa fa-pencil-square-o
                                @elseif($v['name'] == '简历模块')
									glyphicon glyphicon-file
								@elseif($v['name'] == '运营工具')
									fa fa-wrench
                            	@else
									fa fa-cog
                                @endif
							 "></i>
							<span class="menu-text"> {{ $v['name'] }} </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
						@if(!empty($v['childs']))
							<ul class="submenu nav-show">
							@foreach($v['childs'] as $kk => $vv)
									<li class="{{ !empty($currentData['column']) && $currentData['column'] == $vv['column'] ? 'active': ''}}">
										<a href="{{ url($vv['con']) }}">
											<i class="menu-icon fa fa-caret-right"></i>
											{{ $vv['name'] }}
										</a>

										<b class="arrow"></b>
									</li>
							@endforeach
							</ul>
						@endif
					</li>{{-- 内容管理--}}
				@endforeach
			</ul><!-- /.nav-list -->
		@endif
		{{--</ul><!-- /.nav-list -->--}}

		<!-- #section:basics/sidebar.layout.minimize -->
		<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
			<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
		</div>

		<!-- /section:basics/sidebar.layout.minimize -->
		<script type="text/javascript">
			try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
		</script>
	</div>
	<!-- /左侧导航 -->
