<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUnesValuesFromSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('discount_id');
            $table->dropColumn('paid');
            $table->dropColumn('has_discount');
            $table->dropColumn('discount_expire_date');
            $table->dropColumn('discount_expired');
            $table->dropColumn('used');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('discount_id');
            $table->boolean('paid')->default(false);
            $table->boolean('has_discount')->default(false);
            $table->dateTime('discount_expire_date')->nullable();
            $table->boolean('discount_expired')->default(true);
            $table->string('used')->nullable();
        });
    }
}
