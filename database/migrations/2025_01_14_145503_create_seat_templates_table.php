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
        Schema::create('seat_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('matrix_id')->nullable();
            $table->string('name');
            $table->json('seat_structure')->nullable();
            $table->string('row_regular');
            $table->string('row_vip');
            $table->string('row_double');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_publish')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_templates');
    }
};
