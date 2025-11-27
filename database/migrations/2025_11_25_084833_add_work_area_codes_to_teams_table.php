<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkAreaCodesToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {

            $table->unsignedBigInteger('main_work_area_code')->nullable()->after('governorate_id');

            $table->unsignedBigInteger('sub_work_area_code')->nullable()->after('main_work_area_code');

            $table->foreign('main_work_area_code')
                  ->references('id')->on('constants')
                  ->onDelete('set null');

            $table->foreign('sub_work_area_code')
                  ->references('id')->on('constants')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {

            $table->dropForeign(['main_work_area_code']);
            $table->dropForeign(['sub_work_area_code']);

            $table->dropColumn(['main_work_area_code', 'sub_work_area_code']);
        });
    }
}
