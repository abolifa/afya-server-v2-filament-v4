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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->unique();
            $table->enum('type', ['letter', 'archive', 'report', 'other']);
            $table->foreignId('from_contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('from_center_id')->nullable()->constrained('centers')->nullOnDelete();
            $table->date('issue_date');
            $table->json('attachments')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
