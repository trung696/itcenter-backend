<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Payment extends Model
{
    protected $table = 'payment';
    protected $fillable = ['payment_method_id', 'payment_date', 'price', 'description', 'status', 'created_at', 'update_at'];
    // public function dangKi()
    // {
    // 	return $this->belongsTo('App\User');
    // }
    public function saveNewAdmin($params)
    {
        $data = array_merge($params, [
            'payment_method_id' => $params['payment_method_id'],
            'payment_date' => date('Y-m-d H:i:s'),
            'price' => $params['price'],
            'description' => $params['description'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table('payment')->insertGetId($data);
        return $res;
    }
}
