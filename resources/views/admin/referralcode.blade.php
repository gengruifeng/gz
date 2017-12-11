@extends('admin.layouts')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="widget-main">
                <p>
                    <label>是否发放:</label>

                    <select id="issued" name ="issued" >
                        <option {{ !empty($data['issued'])&& $data['issued'] == -1?'selected="selected"':'' }} value="-1">全部</option>
                        <option {{ !empty($data['issued'])&& $data['issued'] == 1?'selected="selected"':'' }} value="1">是</option>
                        <option {{ !empty($data['issued'])&& $data['issued'] == 2?'selected="selected"':'' }} value="2">否</option>

                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <label>是否使用:</label>
                    <select id="used" name ="used" >
                        <option {{ !empty($data['used'])&& $data['used'] == -1?'selected="selected"':'' }} value="-1">全部</option>
                        <option {{ !empty($data['used'])&& $data['used'] == 1?'selected="selected"':'' }} value="1">是</option>
                        <option {{ !empty($data['used'])&& $data['used'] == 2?'selected="selected"':'' }} value="2">否</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button onclick="searchcode()" class="btn btn-danger">搜&nbsp;&nbsp;&nbsp; 索</button>
                </p>
            </div>

            <!-- PAGE CONTENT BEGINS -->
            <div class="table-header">
                邀请码列表
                <button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" type="button">
                    <span class="glyphicon  glyphicon-plus"></span>
                    生成邀请码
                </button>
                <button style="float:right;" onclick="issued()" class="btn btn-primary btn-sm btn-default btn-sm" type="button">
                    <span class="glyphicon  glyphicon-plus"></span>
                    发放邀请码
                </button>
            </div>
            <!-- <div class="table-responsive"> -->
            <!-- <div class="dataTables_borderWrap"> -->
            <div>
                <form id ="issuedFrom" action="{{ url('/admin/ajax/referralcode/issued') }}" method="post">
                    <table id="tablegroup" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
                        <thead>
                        <tr>
                            <th class="center">
                                <label class="position-relative">
                                    <input class="ace" type="checkbox">
                                    <span class="lbl"></span>
                                </label>
                            </th>
                            <th>
                                <label class="position-relative">
                                    ID
                                </label>
                            </th>
                            <th>邀请码</th>
                            <th>是否发放</th>
                            <th>是否使用</th>
                            <th>创建时间</th>
                        </tr>
                        </thead>
                        <tbody id = 'userTbody'>
                        @if(!empty($data['codelist']))
                            @foreach($data['codelist'] as $k=>$v)
                                <tr>
                                    <td class="center">
                                        <label class="position-relative">
                                            <input name="id[]" value="{{ $v->id }}" type="checkbox" class="ace">
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td>
                                        {{ $v->id }}
                                    </td>
                                    <td>{{ $v->code }}</td>
                                    <td>
                                        @if($v->issued > 0)
                                            已发放
                                        @else
                                            未发放
                                        @endif
                                    </td>
                                    <td>
                                        @if($v->used > 0)
                                            已使用
                                        @else
                                            未使用
                                        @endif
                                    </td>
                                    <td>{{ $v->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        {{ csrf_field() }}
                    </table>
                </form>
                <div class="row">
                    @if(!empty($data['codelist']))
                    <div class="col-xs-6">
                        <div class="dataTables_info" id="sample-table-2_info">
                            <span id ='tatol'>{{ $data['pageinfo']['total'] }}</span>条记录，共<span id ='tatolPage'>{{ $data['pageinfo']['allPage'] }}</span>页，当前第<span id ='currenpPge'>{{ $data['pageinfo']['currentPage'] }}</span>页
                            ,每页
                            <select onchange="selectcount(this)" id="pageSize" name="pageSize" class="ui-pg-selbox" role="listbox">
                                <option role="option" value="20" {{ !empty($data['pageSize'])&& $data['pageSize'] == 20?'selected="selected"':'' }}>20</option>
                                <option role="option" value="50" {{ !empty($data['pageSize'])&& $data['pageSize'] == 50?'selected="selected"':'' }}>50</option>
                                <option role="option" value="100" {{ !empty($data['pageSize'])&& $data['pageSize'] == 100?'selected="selected"':'' }}>100</option>
                                <option role="option" value="200" {{ !empty($data['pageSize'])&& $data['pageSize'] == 200?'selected="selected"':'' }}>200</option>
                            </select >条记录
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="dataTables_paginate paging_bootstrap">
                            <ul class="pagination">
                                <li class="prev">
                                    <a href="{{ url("/admin/referralcode/list?pageSize=".$data['pageSize']."&page=".$data['pageinfo']['firstPage']).(!empty($data['used'])&&$data['used'] != -1?'&used='.$data['used']:'').(!empty($data['issued'])&&$data['issued'] != -1?'&issued='.$data['issued']:'') }}">首页</a>
                                </li>
                                <li class="prev">
                                    <a href="{{ url("/admin/referralcode/list?pageSize=".$data['pageSize']."&page=".$data['pageinfo']['prevPage']).(!empty($data['used'])&&$data['used'] != -1?'&used='.$data['used']:'').(!empty($data['issued'])&&$data['issued'] != -1?'&issued='.$data['issued']:'') }}">上一页</a>
                                </li>
                                <li class="prev">
                                    <a href="{{ url("/admin/referralcode/list?pageSize=".$data['pageSize']."&page=".$data['pageinfo']['nextPage']).(!empty($data['used'])&&$data['used'] != -1?'&used='.$data['used']:'').(!empty($data['issued'])&&$data['issued'] != -1?'&issued='.$data['issued']:'') }}">下一页</a>
                                </li>
                                <li class="prev">
                                    <a href="{{ url("/admin/referralcode/list?pageSize=".$data['pageSize']."&page=".$data['pageinfo']['lastPage']).(!empty($data['used'])&&$data['used'] != -1?'&used='.$data['used']:'').(!empty($data['issued'])&&$data['issued'] != -1?'&issued='.$data['issued']:'') }}">尾页</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
@section('javascripts')
    {{--暂时这样  总有一天会加databases--}}
    <script src="{{ asset('admin/js/referralcode.js') }}"></script>
    <script>
        window.onload =function () {

            $(function () {
                $(document).on('click', 'th input:checkbox' , function(){
                    var that = this;
                    $(this).closest('table').find('tr > td:first-child input:checkbox')
                        .each(function(){
                            this.checked = that.checked;
                            $(this).closest('tr').toggleClass('selected');
                        });
                });
            })
        }
    </script>
@endsection



