@extends('admin.default.layout-master')

@section('title',"角色管理-编辑角色")

@section('content')
<article class="page-container">
	<form action="" method="post" class="form form-horizontal" id="form-admin-role-edit">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="name" name="name" datatype="*4-16" nullmsg="角色名称不能为空">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">备注标签：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="description" name="description">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">权限分配：</label>
			<div class="formControls col-xs-8 col-sm-9" id="div-permissions">
				@foreach($permissions as $permission1)
				<dl class="permission-list">
					<dt>
						<label>
							<input type="checkbox" value="{{$permission1['permission']['id']}}" name="{{$permission1['permission']['name']}}" id="{{$permission1['permission']['name']}}">
							{{$permission1['permission']['label']}}</label>
					</dt>
					<dd>
						@foreach($permission1['childPermissions'] as $permission2)
						<dl class="cl permission-list2">
							<dt>
								<label class="">
									<input type="checkbox" value="{{$permission2['permission']['id']}}" name="{{$permission2['permission']['name']}}" id="{{$permission2['permission']['name']}}">
									{{$permission2['permission']['label']}}</label>
							</dt>
							<dd>
								@foreach($permission2['childPermissions'] as $permission3)
								<label class="">
									<input type="checkbox" value="{{$permission3['permission']['id']}}" name="{{$permission3['permission']['name']}}" id="{{$permission3['permission']['name']}}">
									{{$permission3['permission']['label']}}</label>
									@endforeach
							</dd>
						</dl>
					@endforeach
					</dd>
				</dl>
				@endforeach
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<button type="submit" class="btn btn-success radius" id="admin-role-save" name="admin-role-save"><i class="icon-ok"></i> 确定</button>
			</div>
		</div>
	</form>
</article>
@stop


@section('script')
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript">
$(function(){
	$(".permission-list dt input:checkbox").click(function(){
		$(this).closest("dl").find("dd input:checkbox").prop("checked",$(this).prop("checked"));
	});
	$(".permission-list2 dd input:checkbox").click(function(){
		var l =$(this).parent().parent().find("input:checked").length;
		var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
		if($(this).prop("checked")){
			$(this).closest("dl").find("dt input:checkbox").prop("checked",true);
			$(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",true);
		}
		else{
			if(l==0){
				$(this).closest("dl").find("dt input:checkbox").prop("checked",false);
			}
			if(l2==0){
				$(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",false);
			}
		}
	});
	
	$("#form-admin-role-edit").validate({
		rules:{
			roleName:{
				required:true,
			},
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			var name = $('#name').val();
			var description = $('#description').val();
			var div = $('#div-permissions ');
			var inputs = div.find('input:checked ');
			var permissions = "";
			inputs.each(function(){
				permissions += $(this).val() + ',';
			});
			permissions = permissions.substring(0,permissions.length-1);

			$.ajax({
				type: 'POST',
				url: "{{URL::to('/admin/roles').'/'.$id}}" ,
				data: {_token:'{!! csrf_token() !!}',name:name,description:description,permissions:permissions} ,
				success: function(result){
					if(result.errcode == 0){
						alert("操作成功！");
						var index = parent.layer.getFrameIndex(window.name);
						parent.$('.btn-refresh').click();
						parent.layer.close(index);
					}else{
						alert(result.msg);
					}

				},
				error: function(){
					alert('网络异常');
				}
			});


		}
	});
});
</script>
@stop
