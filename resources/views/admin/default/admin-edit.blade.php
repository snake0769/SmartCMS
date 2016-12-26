@extends('admin.default.layout-master')

@section('title',"编辑管理员")

@section('content')

<article class="page-container">
	<form class="form form-horizontal" id="form-admin-edit"  method="post">
		{!! csrf_field() !!}
		<input type="text" class="input-text" value="{{$user['id']}}" placeholder="" id="id" name="id" hidden>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>登录名：</label>
		<div class="formControls col-xs-6 col-sm-7">
			<input type="text" class="input-text" value="{{$user['username']}}" placeholder="" id="username" name="username" disabled>
		</div>
	</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>名称：</label>
			<div class="formControls col-xs-6 col-sm-7">
				<input type="text" class="input-text" value="{{$user['nickname']}}" placeholder="" id="nickname" name="nickname">
			</div>
		</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色：</label>
		<div class="formControls col-xs-6 col-sm-7"> <span class="select-box" style="width:150px;">
			<select class="select" name="roles" size="1">
				<option value="-1"> 请选择</option>
				@foreach($roles as $role)
				<option value="{{$role['id']}}" @if(isset($user['roles'][0]) && $user['roles'][0]['id'] == $role['id']) selected="selected" @endif>{{$role['name']}}</option>
					@endforeach
			</select>
			</span> </div>
	</div>
	<div class="row cl">
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;保存&nbsp;&nbsp;">
		</div>
	</div>
	</form>
</article>
@stop

@section('script')
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
	$("#form-admin-edit").validate({
		rules:{
			id:{
				required:true
			},
			nickname:{
				required:true,
				minlength:4,
				maxlength:16
			},
			role:{
				required:true
			}
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			var loadingIndex = parent.layer.load(1, {shade: [0.3, '#000']});//加入遮罩效果
			$(form).ajaxSubmit({
				type:"post",
				url:"{{URL::to('/admin/users/edit')}}",
				success: function(response){
					parent.layer.close(loadingIndex);
					if(isSuccessful(response)){
						parent.layer.msg('操作成功!', {icon: 1, time: 1000},function(){
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						});
						parent.reload();
					}else{
						parent.layer.alert(response.msg, {icon: 2});
					}

				},
				error: function () {
					parent.layer.close(loadingIndex);
					parent.layer.alert('系统异常', {icon: 2});
				}
			});

		}
	});


});
</script> 
@stop