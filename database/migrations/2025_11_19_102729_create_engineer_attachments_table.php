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
        Schema::create('engineer_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_id')->constrained('engineers')->onDelete('cascade');
            $table->foreignId('attachment_type_id')->constrained('constants')->comment('نوع المرفق من الثوابت');
            $table->string('file_path')->comment('مسار الملف');
            $table->string('file_name')->comment('اسم الملف الأصلي');
            $table->text('details')->nullable()->comment('تفاصيل المرفق');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_attachments');
    }
};