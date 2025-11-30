<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_attendances', function (Blueprint $table) {
            $table->id();
            
            $table->enum('user_type', ['engineer', 'supervisor'])->comment('نوع المستخدم');
            
            $table->foreignId('engineer_id')
                  ->nullable()
                  ->constrained('engineers')
                  ->onDelete('cascade')
                  ->comment('معرف المهندس');
                  
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('معرف المشرف');
            
            $table->date('attendance_date')->comment('تاريخ الدوام');
            $table->enum('day_type', ['weekday', 'friday', 'holiday'])->default('weekday')->comment('نوع اليوم');
            $table->enum('status', ['present', 'absent', 'leave', 'weekend'])->default('present')->comment('حالة الحضور');
            $table->text('notes')->nullable()->comment('ملاحظات');
            
            $table->foreignId('recorded_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('من قام بالتسجيل');
            
            $table->timestamps();
            
            $table->index(['attendance_date', 'user_type']);
            $table->index(['engineer_id', 'attendance_date']);
            $table->index(['user_id', 'attendance_date']);
            
            $table->unique(['engineer_id', 'attendance_date'], 'unique_engineer_attendance');
            $table->unique(['user_id', 'attendance_date'], 'unique_supervisor_attendance');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_attendances');
    }
};