<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHocVienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoc_vien', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->date('ngay_sinh');
            $table->integer('gioi_tinh');
            $table->string('so_dien_thoai');
            $table->string('email');
            $table->string('hinh_anh')->nullable();
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
        Schema::dropIfExists('hoc_vien');
    }
}
