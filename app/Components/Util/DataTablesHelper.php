<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/15
 * Time: 15:14
 */

namespace app\Components\Database;


trait DataTablesHelper
{

    /**
     * 从Model输出的分页器Pagination提出数据，并转换为前端DataTables组件所需的数据格式
     * @param $queryParams array
     * @param $data array
     * @return array
     */
    public function toDataTables($queryParams,$data){
        $afterData = [];
        $afterData['draw'] = !empty(isset($queryParams['draw'])) ? intval($queryParams['draw']) : 1;
        $afterData['recordsTotal'] = $data['total'];
        $afterData['recordsFiltered'] = $data['total'];
        $afterData['data'] = $data['items'];

        return $afterData;
    }


    /**
     * 分页信息转化为偏移位置信息
     * @param $page array|int 分页信息数组，如：['page'=>1,'perPage'=>10] 或 1(只存页数时，则使用默认每页记录数)
     * @return array 如：['start'=>0,'length'=>10]
     */
    public static function convertOffset($page){
        $offset = [];
        if(is_array($page)){
            if(isset($page['perPage'])){
                $length = intval($page['perPage']);
            }else{
                $length = 20;
            }

            $page = isset($page['page']) ? $page['page'] : 1;
        }else{
            $page = intval($page);
            $length = 20;
        }

        $offset['start'] = intval($page) * intval($length);
        $offset['length'] = $length;

        return $offset;
    }

    /**
     * 偏移位置转化为分页信息
     * @param $offset array|int 偏移位置信息数组，如：['start'=>0,'length'=>10] 或 0(只存偏移起始位置时，则使用默认偏移长度)
     * @return array 如：['page'=>1,'perPage'=>10]
     */
    public static function convertPage($offset){
        $page = [];
        if(is_array($offset)){
            if(isset($offset['length'])){
                $perPage = intval($offset['length']);
            }else{
                $perPage = 20;
            }

            $start = isset($offset['start']) ? $offset['start'] : 1;
        }else{
            $start = intval($offset);
            $perPage = 20;
        }

        $page['page'] = intval($start) / intval($perPage) + 1;
        $page['perPage'] = $perPage;

        return $page;
    }



}