@extends('admin.default.layout-master')

@section('title',"添加管理员")

@section('content')
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add"  method="post">
		{!! csrf_field() !!}
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>登录名：</label>
		<div class="formControls col-xs-6 col-sm-7">
			<input type="text" class="input-text" value="" placeholder="" id="username" name="username">
		</div>
	</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>名称：</label>
			<div class="formControls col-xs-6 col-sm-7">
				<input type="text" class="input-text" value="" placeholder="" id="nickname" name="nickname">
			</div>
		</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>密码：</label>
		<div class="formControls col-xs-6 col-sm-7">
			<input type="password" class="input-text" autocomplete="off" value="" placeholder="密码" id="password" name="password">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>确认密码：</label>
		<div class="formControls col-xs-6 col-sm-7">
			<input type="password" class="input-text" autocomplete="off"  placeholder="确认新密码" id="password2" name="password2">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3">角色：</label>
		<div class="formControls col-xs-6 col-sm-7"> <span class="select-box" style="width:150px;">
			<select class="select" name="roles" size="1">
				@foreach($roles as $role)
				<option value="{{$role['id']}}">{{$role['name']}}</option>
					@endforeach
			</select>
			</span> </div>
	</div>
	<div class="row cl">
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
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
	
	$("#form-admin-add").validate({
		rules:{
			username:{
				required:true,
				minlength:4,
				maxlength:16
			},
			nickname:{
				required:true,
				minlength:4,
				maxlength:16
			},
			password:{
				required:true
			},
			password2:{
				required:true,
				equalTo: "#password"
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
				url:"{{URL::to('/admin/users/create')}}",
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