<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('engineers', function (Blueprint $table) {
            $table->string('account_owner_first')->nullable()->change();
            $table->string('account_owner_second')->nullable()->change();
            $table->string('account_owner_third')->nullable()->change();
            $table->string('account_owner_last')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('engineers', function (Blueprint $table) {
            $table->string('account_owner_first')->nullable(false)->change();
            $table->string('account_owner_second')->nullable(false)->change();
            $table->string('account_owner_third')->nullable(false)->change();
            $table->string('account_owner_last')->nullable(false)->change();
        });
    }
};
