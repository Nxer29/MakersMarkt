<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // rol: buyer/maker/moderator (string is makkelijk)
            $table->string('role')->default('buyer')->after('email');

            // account verified status (los van email verified)
            $table->boolean('verified')->default(false)->after('role');

            // winkelkrediet (centen is best practice, maar hier simpel integer)
            $table->integer('store_credit')->default(0)->after('verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'verified', 'store_credit']);
        });
    }
};
