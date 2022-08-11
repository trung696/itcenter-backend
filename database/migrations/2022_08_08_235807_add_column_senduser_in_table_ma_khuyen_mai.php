<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSenduserInTableMaKhuyenMai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_khuyen_mai', function (Blueprint $table) {
            $table->integer('senduser');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ma_khuyen_mai', function (Blueprint $table) {
            $table->dropColumn('senduser');
        });
    }
}
