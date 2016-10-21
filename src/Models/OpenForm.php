<?php

namespace Wisdom\Openform\Models;

use Illuminate\Database\Eloquent\Model;

class OpenForm extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at','created_at'];

    public function question(){
        return $this->hasMany(OpenQuestion::class,'form_id');
    }
}
