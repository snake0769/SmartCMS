<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use ModelMapps;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;


    /**
     * 设置分页器基本路径
     * @param $path
     */
    /*public function setPagerPath($path){
        LengthAwarePaginator::currentPageResolver(function($path){
            return $path;
        });
    }*/

    /**
     * 分页查询
     * @param Builder $query
     * @param Paginator $paginator
     */
    public function paginate($query,$page = null,$columns = ['*'],$perPage = 15,$pageName = 'page'){
        return $query->paginate($perPage,$columns,$pageName,$page);
    }
}
