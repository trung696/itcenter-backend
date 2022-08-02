<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThongTinChuyenLop extends Model
{
    protected $fillable = ['ho_ten', 'email', 'so_dien_thoai','oldClass','newClass','liDo'];
}
