<?php

namespace App\Models;


class SystemConfig extends BaseModel
{
    const KEY_PREFIX_BASE = "admin.base";


    /**
     * 更新系统配置
     * @param $attributes array 新的系统设置 [key]=>[valye]数组
     * @return bool
     * @throws \Exception
     */
    public static function setConfigs($attributes){
        try {
            \DB::beginTransaction();

            foreach ($attributes as $k => $attr) {
                $config = self::where('key', '=', $k)->first();
                $config->value = $attr;
                $config->save();
            }
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
        return true;
    }
}
