<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('engineers', function (Blueprint $table) {
        $table->unsignedBigInteger('specialization_id')->nullable()->after('experience_years');

        $table->foreign('specialization_id')
              ->references('id')
              ->on('constants')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('engineers', function (Blueprint $table) {
        $table->dropForeign(['specialization_id']);
        $table->dropColumn('specialization_id');
    });
}

};
