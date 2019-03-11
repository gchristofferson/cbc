<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('discount_id');
            $table->boolean('paid')->default(false);
            $table->boolean('has_discount')->default(false);
            $table->dateTime('discount_expire_date')->nullable();
            $table->boolean('discount_expired')->default(true);
            $table->string('used')->nullable();
            $table->dateTime('subscription_start_date');
            $table->dateTime('subscription_expire_date');
            $table->unique(['user_id','state_id','discount_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
