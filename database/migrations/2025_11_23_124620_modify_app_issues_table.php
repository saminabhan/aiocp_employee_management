<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('app_issues', function (Blueprint $table) {
            $table->unsignedBigInteger('engineer_id')->nullable()->change();
            
            $table->unsignedBigInteger('user_id')->nullable()->after('engineer_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('app_issues', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropIndex(['status', 'created_at']);
        });
    }
};

