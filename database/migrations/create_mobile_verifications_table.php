<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileVerificationsTables extends Migration
{
    public function __construct()
    {
        $this->userTable = config('verified.user_table');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_verification_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->index();
            $table->string('token', 10)->index();
            $table->timestamp('expires_at')->nullable();

        });

        Schema::table($this->userTable, function (Blueprint $table) {
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

        Schema::table($this->userTable, function (Blueprint $table) {
            $table->dropColumn('mobile_verified_at');
        });
    }
}
