<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChienDichTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chien_dich', function (Blueprint $table) {
            $table->id();
            $table->string('ten_chien_dich');
            $table->integer('phan_tram_giam');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
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
        Schema::dropIfExists('chien_dich');
    }
}
