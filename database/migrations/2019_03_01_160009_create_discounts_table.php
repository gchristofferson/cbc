<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('state_id');
            $table->string('discount')->default('0')->nullable();
            $table->string('discount_desc')->nullable();
            $table->string('days_to_expire_discount')->default('0')->nullable();
            $table->string('override_subscription_expire')->default('off')->nullable();
            $table->string('discount_limit')->default('1')->nullable();
            $table->string('days_to_expire_subscription')->default('365');
            $table->string('promo_code')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
