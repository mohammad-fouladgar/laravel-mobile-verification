<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSentAtInTokensTable extends Migration
{
    private string $tokenTable;

    public function __construct()
    {
        $this->tokenTable = config('mobile_verifier.token_table', 'mobile_verification_tokens');
    }

    public function up(): void
    {
        if (config('mobile_verifier.token_storage') === 'cache') {
            return;
        }

        Schema::table($this->tokenTable, function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable();
        });
    }

    public function down(): void
    {
        if (config('mobile_verifier.token_storage') === 'cache') {
            return;
        }

        Schema::table($this->tokenTable, function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });
    }

}
