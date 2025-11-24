<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('sms_messages', function (Blueprint $table) {
        $table->id();

        $table->string('phone');               // رقم الجوال
        $table->text('message');               // محتوى الرسالة
        $table->string('status')->default('pending'); // pending - sent - failed
        $table->text('api_response')->nullable();     // رد API

        // لتحديد المستلم
        $table->unsignedBigInteger('engineer_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();

        // علاقات
        $table->foreign('engineer_id')->references('id')->on('engineers')->onDelete('set null');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

        $table->timestamps();
    });
}
public function down()
{
    Schema::dropIfExists('sms_messages');
}
};
