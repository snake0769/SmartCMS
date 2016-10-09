<?php

namespace App\Http\Controllers\Admin;

use App\Service\SystemConfigService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SystemConfigsController extends Controller
{

    public function index(){
        $configs = SystemConfigService::getConfigs();
        $data = ['configs'=>$configs];
        return view('admin.default.system-base',$data);
    }
}
