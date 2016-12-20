<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/9
 * Time: 12:00
 */

namespace app\Models;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

trait QuickQuery
{

    /**
     * 基于原Model的with方法，允许设置关联实例的查询字段。备注：查询字段必须包含关联模型的外键字段，如果没有，该函数会自动检测并补充，输出的结果字段中一定包含外键字段
     *
     * @param  array $relations 关联名数组。如：['books'=>'name,author']、['books'=>['name','author']]
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function with($relations)
    {

        $withItems = [];
        if(is_string($relations)){
            $relations = explode(',',$relations);
        }

        //令字段和关联匹配
        foreach ($relations as $name => $fields) {
            //如果只传入关联名，则进行转换并将查询字段设置为'*'
            if (is_numeric($name)) {
                $name = $fields;
                $fields = '*';
            }

            if ($fields instanceof \Closure) {
                $withItems[$name] = $fields;
            } else {
                $withItems[$name] = function ($query) use ($fields) {
                    if (is_string($fields)) {
                        $fields = explode(',', $fields);
                    }

                    //如果关联查询属于HasOneOrMany，则自动补充关联模型的外键字段输出
                    if($query instanceof HasOneOrMany){
                        /**@var $query \Illuminate\Database\Eloquent\Relations\HasOneOrMany* */
                        $foreignKey = $query->getPlainForeignKey();
                        if (array_search($foreignKey, $fields) === false) {
                            $fields[] = $foreignKey;
                        }
                    }
                    //如果关联查询属于BelongsTo或BelongsToMany，则自动补充关联模型的主键字段输出
                    elseif($query instanceof BelongsTo || $query instanceof BelongsToMany){
                        /**@var $query \Illuminate\Database\Eloquent\Relations\BelongsTo* */
                        $otherKey = $query->getOtherKey();
                        if (array_search($otherKey, $fields) === false) {
                            $fields[] = $otherKey;
                        }
                    }

                    $query->select($fields);
                };
            }
        }

        return parent::with($withItems);
    }


}