<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::create('issue_attachments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('issue_id')
      ->constrained('app_issues')
      ->onDelete('cascade');
        $table->foreignId('attachment_type_id')->constrained('constants')->onDelete('restrict');
        $table->string('file_path');
        $table->string('file_name');
        $table->string('mime_type');
        $table->integer('file_size');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_attachments');
    }

};
