<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumPaidDatAndIdPaymentInTableDangKy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dang_ky', function (Blueprint $table) {
            $table->bigInteger('id_payment')->nullable();
            $table->datetime('paid_date')->nullable();
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
            $table->dropColumn('id_payment');
            $table->dropColumn('paid_date');

        });
    }
}
