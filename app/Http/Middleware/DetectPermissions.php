<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/7/1
 * Time: 11:37
 * Name:
 */

namespace App\Http\Middleware;

use App\Exceptions\Error;
use App\Models\RequestUrl;
use Closure;
use Illuminate\Support\Facades\Auth;

class DetectPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->route()->getPath();
        $method = $request->route()->getMethods();
        $requestUrl = RequestUrl::where('url',$path)->where('method',implode(',',$method))->first();

        //找不到相应访问限制，则直接任其通过
        if($requestUrl != null){
            $user = Auth::user();
            if( !$user->hasPermission($requestUrl->permission,false,'name')){
                if($request->wantsJson()){
                   return  response()->json(Error::BUSSINESS_PERMISSION_DENIED);
                }else{
                    return response()->view('admin.default.401');
                }
            }
        }

        return $next($request);
    }
}