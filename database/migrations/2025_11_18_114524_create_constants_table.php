<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('constants', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('parent')->nullable();
        $table->timestamps();
        $table->foreign('parent')->references('id')->on('constants')->onDelete('cascade');
    });
}

    public function down()
    {
        Schema::dropIfExists('constants');
    }
};
