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
 */
function responseSuccess($data=null){
    return response()->json(['errcode'=>0,'data'=>$data]);
}

/**
 * 响应异常,返回状态码errcode和返回数据msg
 */
function responseFalied($errCode=-1,$msg="failed"){
    return response()->json([
        'errcode'=>$errCode==0 ? -1:$errCode,
        'msg'=>$msg]);
}

/**
 * 响应异常,可直接传入Expcetion实例，返回状态码errcode和返回数据msg
 * @param Exception $ex
 */
function responseError($ex){
    if(! $ex instanceof \App\Exceptions\BussinessException){
        \Illuminate\Support\Facades\Log::error($ex);
    }

    return response()->json([
        'errcode'=>$ex->getCode()==0 ? -1:$ex->getCode(),
        'msg'=>$ex->getMessage()]);
}

/**
 * 返回值是否包含错误码，若是，可指定是否抛出错误
 * @param $result
 * @return bool
 */
function isError($result,$throw=false){
    if(is_array($result) && isset($result['errcode'])){
        if($throw)
            throw new \App\Exceptions\BussinessException($result['msg'],$result['errcode']);
        else
            return true;
    }
    else
        return false;
}

