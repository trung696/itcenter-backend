<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormContact extends Model
{
    protected $fillable = ['name','email', 'birthday', 'phone','note','status'];
}
