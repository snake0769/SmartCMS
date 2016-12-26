<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/23
 * Time: 17:06
 */

namespace app\Components\Validation;


use \Illuminate\Http\Request;

trait CaptchaValidation
{
    /**
     * 校验用户登录请求参数,包括验证码
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function validateLogin(Request $request)
    {
        $captcha = \Session::get('captcha');
        $rules = [
            $this->loginUsername() => 'required',
            'password' => 'required',
            'captcha'=>'like:'.$captcha
        ];
        $this->validate($request, $rules);
    }
}