<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $fillable = ['payment_method_id', 'payment_date', 'price', 'description', 'status','created_at','update_at'];
    // public function dangKi()
    // {
    // 	return $this->belongsTo('App\User');
    // }
}
