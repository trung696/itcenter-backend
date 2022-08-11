<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdKhoaHocInTableChienDich extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chien_dich', function (Blueprint $table) {
            $table->unsignedInteger('course_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chien_dich', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });
    }
}
