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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('suppliers');
            $table->string('external_import_id')->unique();
            $table->jsonb('data');
            $table->timestamp('sent_at');
            $table->string('status')->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->integer('total_offers')->default(0);
            $table->integer('processed_offers')->default(0);
            $table->integer('failed_offers')->default(0);
            $table->longText('errors')->nullable();
            $table->timestamps();

            $table->unique(['supplier_id', 'external_import_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
