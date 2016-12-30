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
        $user = \Auth::user();
        dump($user->roles->contains('name','超级管理员'));
        die;
    }

}