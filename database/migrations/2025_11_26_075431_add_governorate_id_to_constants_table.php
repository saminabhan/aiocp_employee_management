<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('constants', function (Blueprint $table) {
            $table->unsignedBigInteger('governorate_id')->nullable();

            $table->foreign('governorate_id')
                ->references('id')
                ->on('constants')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('constants', function (Blueprint $table) {
            $table->dropForeign(['governorate_id']);
            $table->dropColumn('governorate_id');
        });
    }
};
