<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLopHocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lop_hoc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_lop_hoc');
            $table->date('thoi_gian_bat_dau');
            $table->date('thoi_gian_khai_giang');
            $table->integer('so_cho');
            $table->integer('id_dia_diem');
            $table->integer('id_khoa_hoc');
            $table->integer('id_giang_vien');
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lop_hoc');
    }
}
