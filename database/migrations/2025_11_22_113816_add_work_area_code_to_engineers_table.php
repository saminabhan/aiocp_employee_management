<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('engineers', function (Blueprint $table) {
        $table->string('work_area_code')->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('engineers', function (Blueprint $table) {
        $table->dropColumn('work_area_code');
    });
}
};
