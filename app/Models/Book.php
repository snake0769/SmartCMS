<?php

namespace App\Models;


class Book extends BaseModel
{
    public function publisher(){
        return $this->belongsTo(Publisher::class,'publisher_id','id');
    }
}
