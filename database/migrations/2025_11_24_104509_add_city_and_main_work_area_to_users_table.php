<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('city_id')->nullable()->after('governorate_id');
        $table->foreign('city_id')->references('id')->on('constants')->onDelete('set null');

        $table->unsignedBigInteger('main_work_area_code')->nullable()->after('city_id');
        $table->foreign('main_work_area_code')->references('id')->on('constants')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['city_id']);
        $table->dropForeign(['main_work_area_code']);

        $table->dropColumn(['city_id', 'main_work_area_code']);
    });
}
};
