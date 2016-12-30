<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/7/1
 * Time: 11:37
 * Name:
 */

namespace App\Http\Middleware;

use App\Models\Action;
use App\Models\RequestUrl;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class DetectPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $actionName = $request->route()->getActionName();
        $action = Action::where('action',$actionName)->first();

        //超级用户直接开放权限
        \Gate::before(function($user){
            /** @var $user User */
            if($user->isSuperUser()){
                return true;
            }
        });

        if(empty($action) || \Gate::denies($action->permission)){
            if($request->wantsJson()){
                return  response()->json('权限不足');
            }else{
                return response()->view('admin.default.401');
            }
        }

        return $next($request);
    }
    /*public function handle($request, Closure $next)
    {
        $path = $request->route()->getPath();
        $method = $request->route()->getMethods();
        $requestUrl = RequestUrl::where('url',$path)->where('method',implode(',',$method))->first();

        //找不到相应访问限制，则直接任其通过
        if($requestUrl != null){
            $user = Auth::user();
            if( !$user->hasPermission($requestUrl->permission,false,'name')){
                if($request->wantsJson()){
                   return  response()->json('权限不足');
                }else{
                    return response()->view('admin.default.401');
                }
            }
        }

        return $next($request);
    }*/
}