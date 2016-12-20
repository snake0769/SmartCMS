<?php

namespace App\Http\Controllers;

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
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

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

    /**
     * 验证请求参数'id'，主要针对Restful接口。其他接口直接使用validateAndFilter()
     * @param $id
     * @throws BusinessException
     */
    public function validateId($id){
        if(empty($id)){
            throw new BusinessException('请求id不能为空');
        }
    }

    /**
     * 从Model输出的分页器Pagination提出数据，并转换为前端DataTables组件所需的数据格式
     * @param $queryParams array
     * @param $data array
     * @return array
     */
    public function toDataTables($queryParams,$data){
        /*$data = [
            'draw'=>1,
            'recordsTotal'=>5,
            'recordsFiltered'=>5,
            'data'=>[
                ['id'=>1,'username'=>'snake','nickname'=>'snake','roles'=>'admin','created_at'=>'2016-12-14','active'=>1],
                ['id'=>2,'username'=>'snake1','nickname'=>'snake1','roles'=>'admin1','created_at'=>'2016-12-14','active'=>1],
                ['id'=>3,'username'=>'snake2','nickname'=>'snake2','roles'=>'admin2','created_at'=>'2016-12-14','active'=>1]
            ]
        ];
        return $data;*/

        //$data = $pagination->toArray();
        $afterData = [];
        $afterData['draw'] = !empty(isset($queryParams['draw'])) ? intval($queryParams['draw']) : 1;
        $afterData['recordsTotal'] = $data['total'];
        $afterData['recordsFiltered'] = $data['total'];
        $afterData['data'] = $data['items'];

        return $afterData;
    }

}
