<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('engineer_id')
                  ->nullable()
                  ->after('issue_id')
                  ->constrained('engineers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['engineer_id']);
            $table->dropColumn('engineer_id');
        });
    }
};