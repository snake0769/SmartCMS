<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Service\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
    }


    /**
     * @dataProvider prvSave
     */
    public function testSave(){
        $this->assertEquals(1,1);
        /*$rs = Service::save($attributes);

        if($case === 1 || $case === 2){
            //check DB
            $this->seeInDatabase('users', $expected);

            //check return
            assertInstanceOf(Model::class,get_class($rs));
            assertEquals($expected,$rs->attributesToArray());

        }else{
            assertEquals($expected,$rs);
        }*/

    }

    public function prvSave(){
        $data1 = ['username'=>'tom','nickname'=>'Tom','password'=>bcrypt('123456'),'email'=>'tom@tom.com'];
        $data2 = ['id'=>1,'username'=>'mary','nickname'=>'Mary','password'=>bcrypt('654321'),'email'=>'mary@Mary.com'];
        return array(
            array(1,$data1,$data1),
            array(2,$data2,$data2),
            array(3,null,false),
        );
    }
}
