<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/18
 * Time: 17:39
 * Name:
 */



/**
 * 转换restful的参数字符串为参数数组，传入参数格式示例：id=1&name=admin
 * @param string $parameters
 * @return array|null
 */
function getRestfulParameters($parameters){
    if(!empty($parameters)){
        if(strpos($parameters,"&") > 0){
            $parameters = explode("&",$parameters);
            $result = [];
            foreach($parameters as $parameter){
                $parameter = explode("=",$parameter);
                $key = $parameter[0] ? $parameter[0]:null;
                $value = $parameter[1] ? $parameter[1]:null;
                $result = array_merge($result,[$key=>$value]);
            }
            return $result;
        }else{
            return $parameters;
        }
    }
    else
        return null;
}


/**
 * 响应成功,返回状态码errcode和返回数据data
 * @param array null $data
 * @return string
 */
function responseSuccess($data=null){
    return response()->json(['result'=>'success','data'=>$data]);
}

/**
 * 响应异常,返回状态码errcode和返回数据msg
 * @param int $errCode
 * @param string $msg
 * @return string
 */
function responseFailed($errCode=-1, $msg="unknown failed"){
    return response()->json([
        'result'=>'failed',
        'errcode'=>$errCode,
        'msg'=>$msg]);
}

/**
 * 响应异常,可直接传入Expcetion实例，返回状态码errcode和返回数据msg
 * @param Exception $ex
 * @return string
 */
function responseError($ex){
    return response()->json([
        'result'=>'failed',
        'errcode'=>$ex->getCode(),
        'msg'=>$ex->getMessage()]);
}

/**
 * 返回值是否包含错误码，若是，可指定是否抛出错误
 * @param $result
 * @return bool
 * @throws \App\Exceptions\BusinessException
 */
function isError($result,$throw=false){
    if(is_array($result) && isset($result['errcode'])){
        if($throw)
            throw new \App\Exceptions\BusinessException($result['msg'],$result['errcode']);
        else
            return true;
    }
    else
        return false;
}

/**
 * 判断数组元素是否为空
 * @param $key
 * @param array $array
 * @return bool 如果数组指定key对应的元素为空或key不存在，返回true，否则返回false
 */
function array_value_empty($key,array $array){
    return (array_key_exists($key,$array) && !empty($array[$key])) ? false : true;
}

/**
 * 向数组插入非空元素，如果传入元素key或valye为空，则不执行任何操作
 *
 * @param $array array
 * @param $key   string
 * @param $value
 */
function array_insert_nonempty(&$array, $key, $value)
{
    if ($key === '') {
        return;
    } else if (!empty($value)) {
        $array[$key] = $value;
    }
}


/**
 * 对二维数组中的顶层数组的每个元素指定key，该key以第二层数组的指定元素值为其值
 * eg.
 *      $array = [['id'=>'100','name'=>'Ben'],['id'=>'101','name'=>'Tom'],['id'=>'102','name'=>'Mary']];
 *      $key = 'id'
 *      return : ['100'=>['id'=>'100','name'=>'Ben'],'101'=>['id'=>'101','name'=>'Tom'],'102'=>['id'=>'102','name'=>'Mary']]
 *
 * @param array[] $array
 * @param string $key
 * @param boolean $unique 待转换的数组中，指定的key值是否唯一
 *
 * @return array|null
 */
function array_multikey_transform(array $array, $key, $unique = true)
{
    if (empty($key)) {
        return $array;
    }

    if (is_array($array)) {
        $result = [];
        foreach ($array as $subArray) {
            if (!is_array($subArray)) {
                if ($subArray instanceof \Illuminate\Database\Eloquent\Model) {
                    $subArray = $subArray->toArray();
                } elseif (is_object($subArray)) {
                    $subArray = (array)$subArray;
                }
            }
            if (!array_key_exists($key, $subArray)) {
                return false;
            }

            //如果指定的key的对应值在子数组中不是唯一，会重复出现，则构建一个二维的子数组
            if ($unique) {
                $result[$subArray[$key]] = $subArray;
            } else {
                if (!array_key_exists($subArray[$key], $result)) {
                    $result[$subArray[$key]] = [];
                }
                $result[$subArray[$key]][] = $subArray;
            }
        }
        return $result;
    } else {
        return null;
    }

}

/**
 * 对二维数组进行排序，可以元素数组的键值为排序依据
 *
 * @param $array   array 待排序数组
 * @param $orderBy array 排序方式，格式：['id'=>'asc','name'=>'desc']，以元素数组中的键值id顺序、name顺序的方式
 */
function array_multi_sort(&$array, $orderBy)
{
    $sort = function ($a, $b) use ($orderBy) {
        $keys = array_keys($orderBy);

        $mySort = function ($a, $b, $key, $sortType) use (&$mySort, &$keys, $orderBy) {
            if (is_int($a[$key]) || is_int($b[$key])) {
                $rs = intval($a[$key]) - intval($b[$key]);
            } else {
                $rs = strcmp($a[$key], $b[$key]);
            }

            if ($rs > 0) {
                return $sortType == 'asc' ? 1 : -1;
            } else if ($rs < 0) {
                return $sortType == 'asc' ? -1 : 1;
            } else {
                $key = next($keys);
                if ($key !== false) {
                    $sortType = $orderBy[$key];
                    return call_user_func($mySort, $a, $b, $key, $sortType);
                } else {
                    return 0;
                }

            }
        };

        $key = current($keys);
        $sortType = $orderBy[$key];
        return call_user_func($mySort, $a, $b, $key, $sortType);
    };

    usort($array, $sort);
}

/**
 * 向数组指定位置写入key和value
 * @param $array
 * @param $pos
 * @param $key
 * @param $value
 */
function array_position_set(&$array, $pos, $key, $value)
{
    array_splice($array, $pos, 1, $value);
    array_splice($array, $pos + 1, 1, $value + 1);
    $array = array_flip($array);
    array_splice($array, $pos, 1, $key);
    $array = array_flip($array);
}

/**
 * 转化数组中指定key的值为float型
 *
 * @param array $array
 * @param array $keys
 */
function array_float_val(array &$array, array $keys)
{
    if (empty($array)) {
        return;
    }

    foreach ($array as $k => &$v) {
        if (is_array($v)) {
            self::array_float_val($v, $keys);
        } else {
            if (array_search($k, $keys) !== false) {
                $v = floatval($v);
            }
        }
    }
}

/**
 * 根据索引位置，获取数组中对应的键值对
 * @param array $array 数组
 * @param $pos int 索引位置
 * @return array 键值对数组，如:['myKey','123456'];不存在则返回null
 */
function array_key_value(array $array, $pos)
{
    $temp = array_slice($array, $pos, 1, true);
    if (is_array($temp) && count($temp) > 0) {
        $key = array_keys($temp);
        $value = array_values($temp);
        $key = reset($key);
        $value = reset($value);
        return [$key, $value];
    } else {
        return null;
    }

}

/**
 * 重新设置数组的key
 * @param $array
 * @param $key
 * @param $newKey
 */
function array_key_reset(&$array,$key,$newKey){
    if(array_key_exists($key,$array)){
        $value = $array[$key];
        unset($array[$key]);
        $array[$newKey] = $value;
    }
}
