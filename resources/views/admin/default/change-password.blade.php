@extends('admin.default.layout-master')

@section('title',"修改密码")

@section('content')

<article class="page-container">
	<form method="post" class="form form-horizontal" id="form-change-password">
		{!! csrf_field() !!}
		<input type="text" class="input-text" value="{{$user['id']}}" placeholder="" id="id" name="id" hidden>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>登录名：</label>
			<div class="formControls col-xs-8 col-sm-9"> {{$user['username']}} </div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>旧密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="password" class="input-text" autocomplete="off" placeholder="" name="newpassword" id="newpassword">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>新密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="password" class="input-text" autocomplete="off" placeholder="" name="newpassword" id="newpassword">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>确认密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="password" class="input-text" autocomplete="off" placeholder="" name="newpassword2" id="new-password2">
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;保存&nbsp;&nbsp;">
			</div>
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<button class="btn btn-default radius"  value="&nbsp;&nbsp;取消&nbsp;&nbsp;">
			</div>
		</div>
	</form>
</article>
@stop

@section('script')
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript">
$(function(){
	$("#form-change-password").validate({
		rules:{
			newpassword:{
				required:true,
				minlength:6,
				maxlength:16
			},
			newpassword2:{
				required:true,
				minlength:6,
				maxlength:16,
				equalTo: "#newpassword"
			}
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			var loadingIndex = parent.layer.load(1, {shade: [0.3, '#000']});//加入遮罩效果
			$(form).ajaxSubmit({
				type:"post",
				url:"{{URL::to('admin/users/reset_password')}}",
				success: function(response){
					parent.layer.close(loadingIndex);
					if(isSuccessful(response)){
						parent.layer.msg('操作成功!', {icon: 1, time: 1000},function(){
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						});
					}else{
						parent.layer.alert(response.msg, {icon: 2},function(index){
							parent.layer.closeAll();
						});
					}

				},
				error: function () {
					parent.layer.close(loadingIndex);
					parent.layer.alert('系统异常', {icon: 2},function(index){
						parent.layer.closeAll();
					});
				}
			});
		}
	});
});
</script>
@stop