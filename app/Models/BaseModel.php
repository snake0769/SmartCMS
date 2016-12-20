<?php

namespace App\Models;

use app\Components\Database\Builder;
use app\Components\Database\ModelHelper;
use app\Components\Database\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{

    /**
     * 主键数据类型，默认string
     * @var string
     */
    protected $keyType = 'string';

    protected $perPage = 20;

    use QuickQuery,ModelHelper;


    /**
     * 获取Model默认表名
     * @return mixed
     */
    public static function table(){
        $model = new static();
        return $model->getTable();
    }

    /**
     * 获取Model默认每页记录数
     * @return mixed
     */
    public static function perPage(){
        $model = new static();
        return $model->getPerPage();
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return Builder
     */
    public function newQuery()
    {
        return parent::newQuery();
    }

    /**
     * Get a new query builder instance for the connection.(重写了此方法，返回扩展过的QueryBuilder)
     *
     * @return QueryBuilder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    /**
     * Create a new Eloquent query builder for the model.(重写了此方法，返回扩展过的Builder)
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \app\Components\Database\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * 获取基础数据库查询构造器
     * @return QueryBuilder
     */
    public function newBaseQuery(){
        return $this->newQuery()->toBase();
    }


    /**
     * 闲置查找只包含激活态（active=1）的记录
     * @param $query Builder
     * @return Builder
     */
    public function scopeActive($query){
        return $query->where('active','1');
    }

}
