<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id','name','email','password','address','phone','status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
