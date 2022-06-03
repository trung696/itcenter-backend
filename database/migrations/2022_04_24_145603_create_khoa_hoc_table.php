<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhoaHocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_khoa_hoc');
            $table->string('thoi_gian');
            $table->text('thong_tin_khoa_hoc');
            $table->string('hinh_anh');
            $table->integer('hoc_phi');
            $table->integer('id_danh_muc');
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
        Schema::dropIfExists('khoa_hoc');
    }
}
