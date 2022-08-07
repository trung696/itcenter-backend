<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumDaDongAndDuNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dang_ky', function (Blueprint $table) {
            $table->string('so_tien_da_dong')->nullable();
            $table->string('du_no')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dang_ky', function (Blueprint $table) {
            $table->dropColumn('so_tien_da_dong');
            $table->dropColumn('du_no');

        });
    }
}
