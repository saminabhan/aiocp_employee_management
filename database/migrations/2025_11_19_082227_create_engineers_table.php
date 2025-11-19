<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('engineers', function (Blueprint $table) {
            $table->id();

            $table->string('personal_image')->nullable();
            $table->string('national_id')->unique();
            $table->string('first_name');
            $table->string('second_name');
            $table->string('third_name');
            $table->string('last_name');

            $table->string('mobile_1');
            $table->string('mobile_2')->nullable();

            $table->foreignId('gender_id')->constrained('constants');
            $table->foreignId('marital_status_id')->constrained('constants');

            $table->date('birth_date')->nullable();

            $table->foreignId('home_governorate_id')->constrained('constants');
            $table->foreignId('home_city_id')->constrained('constants');
            $table->string('home_address_details')->nullable();

            $table->foreignId('work_governorate_id')->constrained('constants');
            $table->foreignId('work_city_id')->constrained('constants');
            $table->string('work_address_details')->nullable();

            $table->integer('experience_years')->default(0);
            $table->string('specialization')->nullable();
            $table->decimal('salary_amount', 10, 2)->nullable();
            $table->foreignId('salary_currency_id')->constrained('constants');

            $table->date('work_start_date')->nullable();
            $table->date('work_end_date')->nullable();

            $table->string('app_username')->nullable();
            $table->string('app_password')->nullable();

            $table->string('phone_type')->nullable();
            $table->string('phone_name')->nullable();
            $table->string('os_version')->nullable();

            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('iban_number')->nullable();

            $table->string('account_owner_first');
            $table->string('account_owner_second');
            $table->string('account_owner_third');
            $table->string('account_owner_last');

            $table->string('account_owner_national_id')->nullable();
            $table->string('account_owner_mobile')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('engineers');
    }

};
