<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/8
 * Time: 13:05
 */

namespace app\Http\Controllers;


use App\Models\Book;
use App\Models\User;
use App\Service\UserService;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalErrorException;

class TestController extends Controller
{

    public function index(Request $request){
        /*$query1 = User::with(['books'=>function($query){
            $query->where('name','宇宙简史');
        }]);
        $users1 = $query1->get();*/

        /*$query1 = User::leftJoin('boos as b');
        $users1 = $query1->get();*/

        $query2 = \DB::table('books as b')->join('publishers as p','b.publisher_id','=','p.id');
        $query2 = Book::tableQuery();
        $users2 = $query2->get();


        //dump($users1);
        dump($users2);
    }

}