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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->date('event_date');
            $table->time('event_time');
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->foreignId('template_id')->constrained('invitation_templates')->cascadeOnDelete();
            $table->foreignId('created_by_signer_id')->constrained('signers')->cascadeOnDelete();
            $table->foreignId('validated_by_signer_id')->constrained('signers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
