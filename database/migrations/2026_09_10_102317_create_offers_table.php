<?php

declare(strict_types=1);

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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('suppliers');
            $table->foreignId('property_id')
                ->references('id')
                ->on('properties');
            $table->string('external_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('max_guests');
            $table->integer('price');
            $table->string('currency');
            $table->integer('available_units');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['supplier_id', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
