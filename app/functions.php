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
