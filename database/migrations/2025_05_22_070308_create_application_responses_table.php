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
        Schema::create('application_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_application_id')->constrained("user_applications")->onDelete('cascade');
            $table->foreignId('step_id')->constrained('application_steps');
            $table->json('response_data'); // Store form responses as JSON
            $table->boolean('is_completed')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_responses');
    }
};
