<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('system_errors', function (Blueprint $table) {
        $table->id();
        $table->text('message');
        $table->string('file')->nullable();
        $table->integer('line')->nullable();
        $table->string('url')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('ip')->nullable();
        $table->text('user_agent')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_errors');
    }
};
