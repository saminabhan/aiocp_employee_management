<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('engineer_id')->nullable()->after('role_id');

        $table->foreign('engineer_id')
              ->references('id')
              ->on('engineers')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['engineer_id']);
        $table->dropColumn('engineer_id');
    });
}

};
