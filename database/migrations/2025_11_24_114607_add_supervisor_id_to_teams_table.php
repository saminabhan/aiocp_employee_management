<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('teams', function (Blueprint $table) {
        $table->unsignedBigInteger('supervisor_id')->nullable()->after('id');

        $table->foreign('supervisor_id')
              ->references('id')
              ->on('users')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('teams', function (Blueprint $table) {
        $table->dropForeign(['supervisor_id']);
        $table->dropColumn('supervisor_id');
    });
}
};
