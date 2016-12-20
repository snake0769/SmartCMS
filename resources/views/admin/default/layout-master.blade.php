<!DOCTYPE html>
<html lang="en">
<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="{{asset('img/backend/favicon.ico')}}" >
    <LINK rel="Shortcut Icon" href="{{asset('img/backend/favicon.ico')}}" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{asset('js/backend/lib/html5.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/backend/lib/respond.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/backend/lib/PIE_IE678.js')}}"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{{asset('js/backend/static/h-ui/css/H-ui.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('js/backend/static/h-ui.admin/css/H-ui.admin.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('js/backend/lib/Hui-iconfont/1.0.7/iconfont.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('js/backend/lib/icheck/icheck.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('js/backend/static/h-ui.admin/skin/default/skin.css')}}" id="skin" />
    <link rel="stylesheet" type="text/css" href="{{asset('js/backend/static/h-ui.admin/css/style.css')}}" />
    @yield('css')
    <!--[if IE 6]>
    <script type="text/javascript" src="{{asset('js/backend/http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js')}}" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <!--/meta 作为公共模版分离出去-->

    <title>@yield('title')-{{$gTitle}}</title>
    <meta name="keywords" content="{{$gKeywords}}">
    <meta name="description" content="{{$gDescription}}">
</head>
<body>
@yield('content')


<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="{{asset('js/backend/lib/jquery/1.9.1/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/lib/layer/2.1/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/lib/icheck/jquery.icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/lib/jquery.validation/1.14.0/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/lib/jquery.form/3.51.0/jquery.form.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/static/h-ui/js/H-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('js/backend/static/h-ui.admin/js/H-ui.admin.js')}}"></script>
<script type="text/javascript" src="{{asset('js/functions.js')}}"></script>
<!--/_footer /作为公共模版分离出去-->
@yield('script')
</body>
</html>