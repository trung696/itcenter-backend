<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class Thongke extends Model
{
    protected $table = 'thongke';
    protected $fillable = ['id_hocvien', 'id_gv', 'id_class', 'id_course', 'id_dang_ky', 'id_payment'];

    public function createStdClass()
    {
        $objItem = new \stdClass();
        foreach ($this->fillable as $field) {
            $field = substr($field, 4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_gv');
    }
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'id_class');
    }
    public function hocvien()
    {
        return $this->belongsTo(HocVien::class, 'id_hocvien');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'id_course');
    }
    public function dangKy()
    {
        return $this->belongsTo(DangKy::class, 'id_dang_ky');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }


    public function soLopActive()
    {

        $query = DB::table('class', 'tb1')
            ->select('tb1.course_id', 'tb1.location_id', 'tb1.start_date', 'tb1.end_date')
            ->get();

        return $query;
    }
}
