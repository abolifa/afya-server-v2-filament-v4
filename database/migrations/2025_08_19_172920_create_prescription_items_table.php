<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->enum('frequency', ['daily', 'weekly', 'monthly'])->default('daily')->index();
            $table->unsignedTinyInteger('interval')->default(1);
            $table->unsignedTinyInteger('times_per_interval')->default(1);
            $table->decimal('dose_amount');
            $table->string('dose_unit', 20);
            $table->date('start_date')->useCurrent()->index();
            $table->date('end_date')->nullable()->index();
            $table->timestamps();
            $table->unique(['prescription_id', 'product_id'], 'presc_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
