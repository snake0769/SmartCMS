@extends('admin.default.layout-master')

@section('title',"添加管理员")

@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 基本设置 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<form class="form form-horizontal" id="form-edit">
		{!! csrf_field() !!}
		<div id="tab-system" class="HuiTab">
			<div class="tabBar cl"><span>基本设置</span></div>
			<div class="tabCon">
				@foreach($configs as $k=>$config)
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>{{$config['name']}}</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type="text" id="{{$config['key']}}" name="{{$config['key']}}" placeholder="" value="{{$config['value']}}" class="input-text">
						</div>
					</div>
				@endforeach
			<div class="tabCon">
				
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="configs_save();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
				<button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
	</form>
</div>
@stop

@section('script')
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	$.Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");

	$("#form-edit").validate({
		rules:{
			'admin.base.title':{
				required:true
			},
			'admin.base.keywords':{
				required:true
			},
			'admin.base.description':{
				required:true
			},
			'admin.base.assetsRoot':{
				required:true
			},
			'admin.base.uploadRoot':{
				required:true
			},
			'admin.base.footer':{
				required:true
			},
			'admin.base.icp':{
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
				url:"{{URL::to('/admin/system/edit')}}",
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
<!--/请在上方写此页面业务相关的脚本-->
@stop