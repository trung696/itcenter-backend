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
    protected $fillable = ['payment_method_id', 'payment_date', 'price', 'description', 'status', 'id_don_hang', 'id_giao_dich', 'created_at', 'update_at'];
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
    public function sumPay()
    {
        $query = DB::table('payment')
            ->sum('payment.price');
        return $query;
    }
    public function loadpayDay($time)
    {
        // dd($time);
        $query = DB::table('payment')
            ->whereBetween('payment_date', $time)
            ->sum('payment.price');
        // $query = DB::table('payment')->select('payment_date');
        return $query;
    }
    public function loadstd($time)
    {
        $query = DB::table('payment as tb1')
            ->select('tb3.ho_ten as hv_name', 'tb2.id as id đăng ký', 'tb1.id as id payment')
            ->leftJoin('dang_ky as tb2', 'tb1.id', '=', 'tb2.id_payment')
            ->leftJoin('hoc_vien as tb3', 'tb3.id', '=', 'tb2.id_hoc_vien')
            ->whereBetween('tb1.payment_date', $time);
        // $query = DB::table('payment')->select('payment_date');
        return $query->get();
    }
}
