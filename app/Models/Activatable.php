<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/28
 * Time: 13:46
 * Name:
 */

namespace App\Models;


trait Activatable
{

    /**
     * 启用
     * **/
    public function activate(){
        $this->active = 1;
        return $this->save();
    }

    /**
     * 禁用
     * **/
    public function inactivate(){
        $this->active = 0;
        return $this->save();
    }
}