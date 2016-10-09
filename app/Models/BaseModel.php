<?php

namespace App\Models;

use App\Foundation\DatabaseMapper;
use App\Foundation\ModelReflects;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    use ModelReflects;

}
