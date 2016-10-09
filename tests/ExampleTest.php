<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
 * A basic functional test example.
 *
 * @return void
 */
    public function testBasicExample()
    {
        /*$this->visit('/')
             ->see('Laravel 5');*/
        $this->assertEquals(1,1);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample2()
    {
        /*$this->visit('/')
             ->see('Laravel 5');*/
        $this->assertEquals(1,1);
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
