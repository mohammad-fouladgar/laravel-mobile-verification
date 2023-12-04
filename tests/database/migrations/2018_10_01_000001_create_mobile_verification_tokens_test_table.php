<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileVerificationTokensTestTable extends Migration
{
    private string $userTable;

    public function __construct()
    {
        $this->userTable = config('mobile_verifier.user_table');
    }

    public function up(): void
    {
        Schema::create('mobile_verification_tokens', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('mobile')->index();
            $table->string('token', 10)->index();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->index(['mobile', 'token']);
        });

        Schema::table($this->userTable, static function (Blueprint $table): void {
            $table->timestamp('mobile_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::drop('mobile_verification_tokens');
    }
}
