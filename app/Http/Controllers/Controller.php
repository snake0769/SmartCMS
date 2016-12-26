<?php

namespace App\Http\Controllers;

use app\Components\Database\DataTablesHelper;
use App\Exceptions\BusinessException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Factory;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests,DataTablesHelper;

    /**
     * 验证并过滤请求参数，返回过滤后的结果
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     */
    public function validateAndFilter(Request $request, array $rules, array $messages = [], array $customAttributes = []){
        $result = [];
        $params = $request->all();

        foreach($rules as $field=>$rule){
            if(array_key_exists($field,$params)){
                $result[$field] = $params[$field];
            }

            if(empty($rule)){
                unset($rules[$field]);
            }
        }

        $this->validate($request,$rules,$messages,$customAttributes);

        return $result;
    }


}
