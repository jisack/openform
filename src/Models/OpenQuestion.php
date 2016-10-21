<?php

namespace Wisdom\Openform\Models;

use Illuminate\Database\Eloquent\Model;

class OpenQuestion extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at','created_at'];

    public function option(){
        return $this->hasMany(OpenOption::class,'question_id');
    }

    public function answer(){
        return $this->hasMany(OpenAnswer::class,'question_id');
    }
}
