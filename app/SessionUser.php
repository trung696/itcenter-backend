<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionUser extends Model
{
    protected $fillable = ['token','refresh_token','expired_token','refresh_expired_token','user_id'];
    public function hocVien(){
        return $this->belongsTo(HocVien::class,'user_id','id');
    }
}

