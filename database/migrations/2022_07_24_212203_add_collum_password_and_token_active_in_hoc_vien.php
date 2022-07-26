<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumPasswordAndTokenActiveInHocVien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hoc_vien', function (Blueprint $table) {
            $table->string('password');
            $table->string('tokenActive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hoc_vien', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('tokenActive');
        });
    }
}
