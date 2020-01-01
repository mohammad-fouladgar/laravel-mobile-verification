<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileVerificationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userTable = config('verified.user_table');

        Schema::create('mobile_verification_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        //TODO: get user table name dynamicly
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('mobile_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mobile_verification_tokens');

        //TODO: get user table name dynamicly
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile_verified_at');
        });
    }
}
