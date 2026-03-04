<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maker_id')->constrained('users')->cascadeOnDelete();

            $table->string('name');
            $table->text('description');

            $table->string('type');
            $table->text('material');

            $table->string('production_time');
            $table->string('complexity');

            $table->text('durability');
            $table->text('unique_features');

            $table->boolean('verified')->default(false);
            $table->boolean('flagged')->default(false);

            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
