@extends('admin.default.layout-master')

@section('title',"管理员列表")

@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c"> 加入日期：
		<input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}'})" id="datemin" class="input-text Wdate" style="width:120px;">
		-
		<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d'})" id="datemax" class="input-text Wdate" style="width:120px;">
		<input type="text" class="input-text" style="width:250px" placeholder="输入登录名、昵称" id="keyword" name="">
		<button type="submit" class="btn btn-success radius" id="" name="" onclick="search()"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
	</div>
	<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="batch_admin_del()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a href="javascript:;" onclick="admin_add('添加管理员','{{URL::to('/admin/users/create')}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加管理员</a></span>
		</div>
	<table id='dataTable' class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="9">管理员列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="" value=""></th>
				<th width="25">序号</th>
				<th width="150">ID</th>
				<th width="120">登录名</th>
				<th width="120">昵称</th>
				<th width="120">角色</th>
				<th width="120">加入时间</th>
				<th width="80">状态</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		{{--@foreach($users as $user)
			<tr class="text-c">
				<td><input type="checkbox" name="item" id="{{$user['id']}}"></td>
				<td>{{$user['id']}}</td>
				<td>{{$user['username']}}</td>
				<td>{{$user['nickname']}}</td>
				<td>@if(isset($user['roles'][0])){{$user['roles'][0]['name']}}@endif</td>
				<td>{{$user['created_at']}}</td>
				<td class="td-status">
						@if(intval($user['active']) == 1)
						<span class="label label-success radius">已启用</span>
							@else
						<span class="label radius">已停用</span>
							@endif
				</td>
				<td class="td-manage">
					@if(intval($user['active']) == 1)
					<a style="text-decoration:none" onClick="admin_stop(this,'{{$user['id']}}')" href="javascript:" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>
					@else
						<a style="text-decoration:none" onClick="admin_start(this,'{{$user['id']}}')" href="javascript:" title="启用"><i class="Hui-iconfont">&#xe615;</i></a>
					@endif
					<a title="编辑" href="javascript:;" onclick="admin_edit('管理员编辑','{{URL::to('/admin/users/'.$user['id'].'/edit')}}','2','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
					<a title="删除" href="javascript:;" onclick="admin_del(this,'{{$user['id']}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr>
		@endforeach--}}

		</tbody>
	</table>
    </div>
</div>
	@stop

@section('script')
<script type="text/javascript" src="{{asset('js/backend/lib/My97DatePicker/WdatePicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/lib/datatables/1.10.0/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/static/default/datatables-helper.js')}}?2016122001"></script>

<script type="text/javascript">

	var table = null;

	var tableAjax = function(data,callback,settings){
		$.ajax({
			type: 'GET',
			data: getSearchParams(data),
			traditional:true,
			url: "{{URL::to('/admin/users/list')}}",
			success: function (response) {
				if (isSuccessful(response)) {
					callback(response.data)
				} else {
					alert(response.msg);
				}

			},
			error: function () {
				alert('网络异常');
			}
		});
	};

	var renderCheckbox = function(data,type,row){
		return '<input type="checkbox" name="item" id="' + row.id + '">';
	};

	var renderActive = function(data,type,row){
		var html = '';
		if(parseInt(row.active) == 1){
			html = '<span class="label label-success radius">已启用</span>';
		}else{
			html = '<span class="label radius">已停用</span>';
		}
		return html;
	};

	var renderOperation = function(data,type,row){
		var html = '';
		var editUrl = '{{URL::to('/admin/users/edit?id={0}')}}'.format(row.id);
		if(parseInt(row.active) == 1){
			html += '<a style="text-decoration:none" onClick="' + "admin_stop(this,'{0}')".format(row.id) + 'href="javascript:" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>';
		}else{
			html += '<a style="text-decoration:none" onClick="' + "admin_start(this,'{0}')".format(row.id) + 'href="javascript:" title="启用"><i class="Hui-iconfont">&#xe615;</i></a>';
		}
		html += '<a title="编辑" href="javascript:;" onclick="' + "admin_edit('管理员编辑','{0}','2','800','500')".format(editUrl) + '" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>';
		html += '<a title="删除" href="javascript:;" onclick="' + "admin_del(this,'{0}')".format(row.id) + '" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>';

		return html
	};

	// 字段映射表
	var cols = [
		{name:null,render:renderCheckbox},
		{name:null},
		'id',
		'username',
		'nickname',
		{name:'roles',orderable:false},
		'created_at',
		{name:'active',render:renderActive,className:'td-status'},
		{name:null,render:renderOperation,className:'td-manage'}
	];

	/**
	 * 获取查询参数
	 * data:DataTables组件自带的查询参数，包含了页数、每页记录数等信息
	 **/
	function getSearchParams(data){
		var params = {
			start_date:$('#datemin').val(),
			end_date:$('#datemax').val(),
			keyword:$('#keyword').val(),
			draw:data.draw,
			start:data.start,
			length:data.length,
			order:getOrderParams(data,cols)
		};
		return params;
	}

	$(function () {
		table = $('#dataTable').DataTable({
			processing:true,
			serverSide:true,
			lengthChange:false,
			searching:false,
			order:[],
			rowCallback:addRowClass,
			pageLength:3,
			ajax:tableAjax,
			columns:getColumnsSet(cols),
			drawCallback:drawSequence
		});
	});

	//注册快捷搜索按键
	$(document).ready(function(){
		$("#keyword").keydown(function(event){
			if(event.which == 13)
				search();
		});
	});

	/*管理员-搜索*/
	function search() {
		table.ajax.reload();
	}

	/*
	 管理员-增加
	 title	标题
	 url		请求的url
	 id		需要操作的数据id
	 w		弹出层宽度（缺省调默认值）
	 h		弹出层高度（缺省调默认值）
	 */
	function admin_add(title, url, w, h) {
		layer_show(title, url, w, h);
	}

	/*管理员-删除*/
	function admin_del(obj, id) {
		layer.confirm('确认要删除吗？', function (index) {
			//此处请求后台程序，下方是成功后的前台处理……
			$.ajax({
				type: 'DELETE',
				url: "{{URL::to('/admin/users')}}" + "/" + id,
				data: {_token: '{!! csrf_token() !!}'},
				success: function (result) {
					if (result.errcode == 0) {
						//$(obj).parents("tr").remove();
						layer.msg('已删除!', {icon: 1, time: 1000});
						setTimeout(function () {
							location.replace(location.href);
						}, 1000);
					} else {
						alert(result.msg);
					}

				},
				error: function () {
					alert('网络异常');
				}
			});

		});
	}

	/*管理员-编辑*/
	function admin_edit(title, url, id, w, h) {
		layer_show(title, url, w, h);
	}
	/*管理员-停用*/
	function admin_stop(obj, id) {
		//此处请求后台程序，下方是成功后的前台处理……
		layer.confirm('确认要停用吗？', function (index) {

			$.ajax({
				type: 'POST',
				url: "{{URL::to('/admin/users/activate')}}",
				data: {id: id, active: 0, _token: '{!! csrf_token() !!}'},
				success: function (result) {
					if (result.errcode == 0) {
						/*$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,'+id+')" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
						 $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
						 $(obj).remove();*/
						layer.msg('已停用!', {icon: 5, time: 1000});
						setTimeout(function () {
							location.replace(location.href);
						}, 1000);
					} else {
						alert(result.msg);
					}

				},
				error: function () {
					alert('网络异常');
				}
			});


		});
	}

	/*管理员-启用*/
	function admin_start(obj, id) {
		layer.confirm('确认要启用吗？', function (index) {
			$.ajax({
				type: 'POST',
				url: "{{URL::to('/admin/users/activate')}}",
				data: {id: id, active: 1, _token: '{!! csrf_token() !!}'},
				success: function (result) {
					if (result.errcode == 0) {
						/*$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,'+id+')" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
						 $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
						 $(obj).remove();*/
						layer.msg('已启用!', {icon: 6, time: 1000});
						setTimeout(function () {
							location.replace(location.href);
						}, 1000);
					} else {
						alert(result.msg);
					}

				},
				error: function () {
					alert('网络异常');
				}
			});


		});
	}


	/*管理员-批量删除*/
	function batch_admin_del() {

		var ids = getCheckedInput();

		layer.confirm('确认要删除吗？', function (index) {
			$.ajax({
				type: 'DELETE',
				url: "{{URL::to('/admin/users/batch_delete')}}",
				data: {ids: ids, _token: '{!! csrf_token() !!}'},
				success: function (result) {
					if (result.errcode == 0) {
						//$(obj).parents("tr").remove();
						layer.msg('已删除!', {icon: 1, time: 1000});
						setTimeout(function () {
							location.replace(location.href);
						}, 1000);
					} else {
						alert(result.msg);
					}

				},
				error: function () {
					alert('网络异常');
				}
			});

		});
	}

</script>
@stop