<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // alleen toevoegen als je deze nog niet hebt
            if (!Schema::hasColumn('users', 'verified')) {
                $table->boolean('verified')->default(false)->after('email_verified_at');
            }

            // optioneel: alleen als jouw app dit echt gebruikt en het nog niet bestaat
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'store_credit')) {
                $table->integer('store_credit')->default(0)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'verified')) $table->dropColumn('verified');
            if (Schema::hasColumn('users', 'role')) $table->dropColumn('role');
            if (Schema::hasColumn('users', 'store_credit')) $table->dropColumn('store_credit');
        });
    }
};
