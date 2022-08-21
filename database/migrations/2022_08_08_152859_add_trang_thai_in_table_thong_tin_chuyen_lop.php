<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrangThaiInTableThongTinChuyenLop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thong_tin_chuyen_lops', function (Blueprint $table) {
            $table->boolean('trang_thai')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thong_tin_chuyen_lops', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
}
