<?php

namespace App\Http\Controllers\Admin;

use App\Service\SystemConfigService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SystemConfigsController extends Controller
{

    /**
     * 进入系统设置
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $configs = SystemConfigService::instance()->getConfigs();
        $data['configs'] = $configs;
        return view('admin.default.system-base',$data);
    }

    /**
     * 编辑系统设置
     * @param Request $request
     * @return string
     */
    public function edit(Request $request)
    {
        $params = $this->validateAndFilter($request, [
            'admin_base_title' => 'required',
            'admin_base_keywords' => 'required',
            'admin_base_description' => 'required',
            'admin_base_assetsRoot' => 'required',
            'admin_base_uploadRoot' => 'required',
            'admin_base_footer' => 'required',
            'admin_base_icp' => 'required'
        ]);
        //把参数中的key值进行字符串转换,如admin_base_title转换为admin.base.title
        foreach($params as $key => $param){
            $newKey = str_replace('_','.',$key);
            array_key_reset($params,$key,$newKey);
        }

        SystemConfigService::instance()->update($params);
        return responseSuccess();
    }
}
