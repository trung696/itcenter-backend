<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ca extends Model
{
    protected $table = 'cas';
    protected $fillable =[ 'ca_hoc','trang_thai','created_at','updated_at'];
}
