<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ColumnsController extends Controller
{
    public function index(){
        return view('admin.default.system-columns');
    }

    public function create(){
        return view('admin.default.system-columns-add');
    }
}
