<?php

use Illuminate\Config\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileVerificationTokensTable extends Migration
{
    /**
     * @var Repository
     */
    private $userTable;

    /**
     * @var Repository
     */
    private $tokenTable;

    /**
     * @var Repository
     */
    private $mobileColumn;

    /**
     * CreateMobileVerificationTokensTable constructor.
     */
    public function __construct()
    {
        $this->userTable    = config('mobile_verifier.user_table', 'users');
        $this->mobileColumn = config('mobile_verifier.mobile_column', 'mobile');
        $this->tokenTable   = config('mobile_verifier.token_table', 'mobile_verification_tokens');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tokenTable, static function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->index();
            $table->string('token', 10)->index();
            $table->timestamp('expires_at')->nullable();

            $table->index(['mobile', 'token']);
        });

        if (!Schema::hasColumn($this->userTable, $this->mobileColumn)) {
            Schema::table($this->userTable, function (Blueprint $table) {
                $table->string($this->mobileColumn);
            });
        }

        if (!Schema::hasColumn($this->userTable, 'mobile_verified_at')) {
            Schema::table($this->userTable, function (Blueprint $table) {
                $table->timestamp('mobile_verified_at')->nullable()->after($this->mobileColumn);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop($this->tokenTable);

        Schema::table($this->userTable, static function (Blueprint $table) {
            $table->dropColumn('mobile_verified_at');
        });
    }
}
