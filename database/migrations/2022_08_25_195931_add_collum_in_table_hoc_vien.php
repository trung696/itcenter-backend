<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumInTableHocVien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hoc_vien', function (Blueprint $table) {
            $table->string('cccd');
            $table->string('imgTruocCccd')->nullable();
            $table->string('imgSauCccd')->nullable();
            $table->string('address')->nullable();
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
            $table->dropColumn('cccd');
            $table->dropColumn('imgTruocCccd');
            $table->dropColumn('imgSauCccd');
            $table->dropColumn('address');
        });
    }
}
