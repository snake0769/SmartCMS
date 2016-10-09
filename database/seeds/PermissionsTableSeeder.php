<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //管理员管理
        $rootId = $this->generateTopPermission("admin.users","允许管理员管理");
        $this->generateBasePermission($rootId,['admin.users'=>'管理员','admin.roles'=>'角色'],"index");
        $this->generatePermission($rootId,['admin.permissions'=>'权限'],"index",['index'=>'管理','show'=>'查看','edit'=>'编辑']);
        //系统管理
        $rootId = $this->generateTopPermission("admin.system","允许系统管理");
        $this->generatePermission($rootId,['admin.system'=>'系统选项'],"index",['index'=>'管理','show'=>'查看','edit'=>'编辑']);
        $this->generatePermission($rootId,['admin.logs'=>'操作日志'],"index",['index'=>'管理','show'=>'查看']);

    }


    /**
     * 生成的顶层权限插入字段数组（layer=1）
     * @param array $permissions
     */
    private function generateTopPermission($name,$label){
        $data = [
            "name" => $name,"label" => $label,"pid"=>0,"layer"=>1,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        return DB::table("permissions")->insertGetId($data);
    }

    /**
     * 生成基本 增删查改 权限插入字段的数组（layer=2|3）
     * @param array $obj
     * @param string $parent
     */
    private function generateBasePermission($rootId, $obj, $parent){
        $ids = [];
        foreach($obj as $objName=>$objLabel){
            $ops = $this->getOperations();
            foreach($ops as $opName=>$opLabel){
                $pName = $opName == $parent? "":".$parent";
                $layer = $opName == $parent?2:3;

                //如果是parent op，则将其pid设置为rootId
                if($opName === $parent){
                    $opLabel = $objLabel;
                    $pid = $rootId;
                }
                //以第一个op(操作)为其他op的parent，设置相应的pid
                elseif( isset($ids[$objName.$pName]) ){
                    $pid = $ids[$objName.$pName];
                }else
                    $pid = 0;

                $data = [
                    "name" => $objName.".$opName","label" => $opLabel,"pid"=>$pid,"layer"=>$layer,
                    "created_at" => Carbon::now()->toDateTimeString(),
                    "updated_at" => Carbon::now()->toDateTimeString()
                ];
                $ids[$objName.".$opName"] = DB::table("permissions")->insertGetId($data);
            }

        }
    }

    /**
     * 获取基本操作名称和标签
     */
    private function getOperations(){
        return ['index'=>'管理','show'=>'查看','create'=>'添加','edit'=>'编辑','delete'=>'删除'];//第一个操作必须是layer=2
    }


    /**
     * 生成 权限插入字段的数组
     * @param array $obj
     */
    private function generatePermission($rootId,$obj, $parent, $ops){
        foreach($obj as $objName=>$objLabel){
            foreach($ops as $opName=>$opLabel){
                $pName = $opName == $parent? "":".$parent";
                $layer = $opName == $parent?2:3;

                //如果是parent op，则将其pid设置为rootId
                if($opName === $parent){
                    $opLabel = $objLabel;
                    $pid = $rootId;
                }
                //以第一个op(操作)为其他op的parent，设置相应的pid
                elseif( isset($ids[$objName.$pName]) ){
                    $pid = $ids[$objName.$pName];
                }else
                    $pid = 0;

                $data = [
                    "name" => $objName.".$opName","label" => $opLabel,"pid"=>$pid,"layer"=>$layer,
                    "created_at" => Carbon::now()->toDateTimeString(),
                    "updated_at" => Carbon::now()->toDateTimeString()
                ];
                $ids[$objName.".$opName"] = DB::table("permissions")->insertGetId($data);
            }

        }
    }

}
