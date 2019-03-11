<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('company_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('license');
            $table->string('main_market')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('avatar')->default('placeholder.gif');
            $table->string('background_img')->default('up-user-details-background-3.jpg');
            $table->string('agree')->default('off');
            $table->string('approved')->default('off')->nullable();
            $table->string('rejected')->default('off')->nullable();
            $table->string('admin')->default('off')->nullable();
            $table->string('super_admin')->default('off')->nullable();
            $table->string('notifications')->default('on')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
