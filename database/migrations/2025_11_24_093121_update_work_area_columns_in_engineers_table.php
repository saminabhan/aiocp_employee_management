<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('engineers', function (Blueprint $table) {

            if (Schema::hasColumn('engineers', 'work_area_code')) {
                $table->renameColumn('work_area_code', 'main_work_area_code');
            }

            if (!Schema::hasColumn('engineers', 'sub_work_area_code')) {
                $table->unsignedBigInteger('sub_work_area_code')
                    ->nullable()
                    ->after('main_work_area_code');
            }
        });


        Schema::table('engineers', function (Blueprint $table) {
            if (Schema::hasColumn('engineers', 'main_work_area_code')) {
                $table->unsignedBigInteger('main_work_area_code')->nullable()->change();
            }

            if (Schema::hasColumn('engineers', 'sub_work_area_code')) {
                $table->unsignedBigInteger('sub_work_area_code')->nullable()->change();
            }
        });


        Schema::table('engineers', function (Blueprint $table) {

            if (Schema::hasColumn('engineers', 'main_work_area_code')) {
                $table->foreign('main_work_area_code')
                    ->references('id')->on('constants')
                    ->nullOnDelete();
            }

            if (Schema::hasColumn('engineers', 'sub_work_area_code')) {
                $table->foreign('sub_work_area_code')
                    ->references('id')->on('constants')
                    ->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('engineers', function (Blueprint $table) {

            if (Schema::hasColumn('engineers', 'main_work_area_code')) {
                $table->dropForeign(['main_work_area_code']);
            }

            if (Schema::hasColumn('engineers', 'sub_work_area_code')) {
                $table->dropForeign(['sub_work_area_code']);
            }

            if (Schema::hasColumn('engineers', 'sub_work_area_code')) {
                $table->dropColumn('sub_work_area_code');
            }

            if (Schema::hasColumn('engineers', 'main_work_area_code')) {
                $table->renameColumn('main_work_area_code', 'work_area_code');
            }
        });
    }
};
