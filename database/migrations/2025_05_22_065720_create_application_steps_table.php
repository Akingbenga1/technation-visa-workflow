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
        Schema::create('application_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained('application_stages')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->integer('order');
            $table->string('form_type'); // simple, document_upload, checklist, etc.
            $table->json('form_schema')->nullable(); // JSON schema for the form
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_steps');
    }
};
