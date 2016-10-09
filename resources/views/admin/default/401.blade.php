@extends('admin.default.layout-master')

@section('title',"401页面")

@section('content')

  <section class="container-fluid page-404 minWP text-c">
  <p class="error-title"><i class="Hui-iconfont va-m" style="font-size:80px">&#xe688;</i><span class="va-m"> 401</span></p>
  <p class="error-description">不好意思，您的权限不足~</p>
  {{--<p class="error-info">您可以：<a href="javascript:;" onclick="history.go(-1)" class="c-primary">&lt; 返回上一页</a><span class="ml-20">|</span><a href="{{URL::to('/admin/index/index')}}" class="c-primary ml-20">去首页 &gt;</a></p>--}}
</section>
