<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_engineer_syncs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('engineer_id')
                  ->constrained('engineers')
                  ->cascadeOnDelete()
                  ->comment('المهندس');

            $table->date('sync_date')->comment('تاريخ المزامنة');

            $table->boolean('is_synced')
                  ->default(true)
                  ->comment('هل تمت المزامنة');

            $table->text('notes')->nullable()->comment('ملاحظات');

            $table->foreignId('recorded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('من قام بالتسجيل');

            $table->timestamps();

            $table->unique(['engineer_id', 'sync_date'], 'unique_engineer_sync');
            $table->index(['sync_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_engineer_syncs');
    }
};
