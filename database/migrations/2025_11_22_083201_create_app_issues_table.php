<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('engineer_id')
                  ->constrained('engineers')
                  ->onDelete('cascade');

            $table->foreignId('problem_type_id')
                  ->constrained('constants')
                  ->onDelete('restrict');

            $table->text('description');

            $table->enum('status', ['open', 'in_progress', 'closed'])
                  ->default('open');

            $table->enum('priority', ['low', 'medium', 'high'])
                  ->default('medium');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_issues');
    }
};
