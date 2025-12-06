<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('system_errors', function (Blueprint $table) {
        $table->string('type')->nullable()->after('line');
        $table->longText('trace')->nullable()->after('type');
    });
}

public function down()
{
    Schema::table('system_errors', function (Blueprint $table) {
        $table->dropColumn(['type', 'trace']);
    });
}
};
