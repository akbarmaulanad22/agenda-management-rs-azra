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
        Schema::table('agendas', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('agendas', 'letter_place')) {
                $table->string('letter_place')->nullable()->after('title');
            }
            if (!Schema::hasColumn('agendas', 'letter_recipient')) {
                $table->text('letter_recipient')->nullable()->after('letter_number');
            }
            if (!Schema::hasColumn('agendas', 'letter_body')) {
                $table->text('letter_body')->nullable()->after('letter_recipient');
            }

            // Make template_id nullable
            $table->foreignId('template_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn(['letter_place', 'letter_recipient', 'letter_body']);
        });
    }
};
