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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->unique();
            $table->string('qr_code')->unique()->nullable();
            $table->date('issue_date');
            $table->enum('type', ['internal', 'external'])->index();
            $table->foreignId('to_center_id')->nullable()->constrained('centers')->nullOnDelete();
            $table->foreignId('to_contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('templates')->nullOnDelete();
            $table->foreignId('follow_up_id')->nullable()->constrained('letters')->nullOnDelete();
            $table->string('subject')->nullable();
            $table->string('to')->nullable();
            $table->text('body')->nullable();
            $table->json('tags')->nullable();
            $table->json('cc')->nullable();
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
        Schema::dropIfExists('letters');
    }
};
