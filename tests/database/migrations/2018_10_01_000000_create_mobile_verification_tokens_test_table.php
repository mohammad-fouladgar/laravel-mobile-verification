<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileVerificationTokensTestTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mobile_verification_tokens', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->index();
            $table->string('token', 10)->index();
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('mobile_verification_tokens');
    }
}