<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/14
 * Time: 17:17
 */

namespace app\Components\Database;


use app\Components\Util\BuilderHelper;
use Illuminate\Database\Query\Builder;

class QueryBuilder extends Builder
{

    use BuilderHelper;

    /**
     * 设定偏移位置和长度，执行select操作查询部分记录
     * @param $startIndex int 偏移位置，即页数对应起点
     * @param $length int 每页记录数，即每页记录数
     * @param $cols array 过滤字段
     * @return Builder
     */
    public function part($startIndex,$length,$cols=['*']){
        $page = intval($startIndex) / intval($length);
        $total = $this->count();
        $records =  $this->forPage($page,$length)->get($cols);
        return ['total'=>$total,'startIndex'=>intval($startIndex),'length'=>intval($length),'items'=>$records];
    }


}