<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileVerificationTokensTable extends Migration
{
    private string $userTable;

    private string $tokenTable;

    private string $mobileColumn;

    public function __construct()
    {
        $this->userTable    = config('mobile_verifier.user_table', 'users');
        $this->mobileColumn = config('mobile_verifier.mobile_column', 'mobile');
        $this->tokenTable   = config('mobile_verifier.token_table', 'mobile_verification_tokens');
    }

    public function up(): void
    {
        $this->tokenTableUp();

        if (!Schema::hasColumn($this->userTable, $this->mobileColumn)) {
            Schema::table(
                $this->userTable,
                function (Blueprint $table): void {
                    $table->string($this->mobileColumn);
                }
            );
        }

        if (!Schema::hasColumn($this->userTable, 'mobile_verified_at')) {
            Schema::table(
                $this->userTable,
                function (Blueprint $table): void {
                    $table->timestamp('mobile_verified_at')->nullable()->after($this->mobileColumn);
                }
            );
        }
    }

    public function down(): void
    {
        $this->tokenTableDown();

        Schema::table(
            $this->userTable,
            static function (Blueprint $table): void {
                $table->dropColumn('mobile_verified_at');
            }
        );
    }

    private function tokenTableUp(): void
    {
        if (config('mobile_verifier.token_storage') === 'cache') {
            return;
        }

        Schema::create(
            $this->tokenTable,
            static function (Blueprint $table): void {
                $table->increments('id');
                $table->string('mobile')->index();
                $table->string('token', 10)->index();
                $table->timestamp('expires_at')->nullable();

                $table->index(['mobile', 'token']);
            }
        );
    }

    private function tokenTableDown(): void
    {
        if (config('mobile_verifier.token_storage') === 'cache') {
            return;
        }

        Schema::drop($this->tokenTable);
    }
}
