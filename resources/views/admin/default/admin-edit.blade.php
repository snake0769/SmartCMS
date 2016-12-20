@extends('admin.default.layout-master')

@section('title',"编辑管理员")

@section('content')

<article class="page-container">
	<form class="form form-horizontal" id="form-admin-edit"  method="post">
		{!! csrf_field() !!}
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
			$(form).ajaxSubmit({
				type:"put",
				url:"{{URL::to('/admin/users/'.$user['id'])}}",
				success: function(response){
					if(response.result == 'success'){
						alert("操作成功!");
						var index = parent.layer.getFrameIndex(window.name);
						parent.$('.btn-refresh').click();
						parent.layer.close(index);
					}else{
						alert(response.msg);
					}

				},
				error: function () {
					alert("系统异常!");
				}
			});

		}
	});


});
</script> 
@stop