<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_id')->unique();
            $table->boolean('on_work_hour')->default(true)->index();
            $table->unsignedBigInteger('hn')->index();
            $table->string('name', 128);
            $table->date('dob')->nullable()->index();
            $table->unsignedTinyInteger('gender')->index();
            $table->boolean('anonymouse')->default(true);
            $table->string('origin', 30)->index();
            $table->string('chief_complaint')->nullable();
            $table->string('zone_type', 120)->nullable();
            $table->string('zone_name', 120)->nullable();
            $table->string('tag_number', 60)->nullable();
            $table->string('movement', 60)->nullable();
            $table->unsignedTinyInteger('severity_level')->nullable();
            $table->string('insurance', 120)->nullable();
            $table->dateTime('triaged_at')->nullable()->index();
            $table->boolean('cpr')->default(false)->index();
            $table->boolean('tube')->default(false)->index();
            $table->boolean('observe')->default(false)->index();
            $table->string('diagnosis')->nullable();
            $table->unsignedTinyInteger('sbp')->nullable();
            $table->unsignedTinyInteger('dbp')->nullable();
            $table->decimal('body_temperature_celsius', 3, 1, true)->nullable();
            $table->unsignedTinyInteger('pulse_per_minute')->nullable();
            $table->unsignedTinyInteger('respiration_rate_per_minute')->nullable();
            $table->unsignedTinyInteger('o2_sat')->nullable();
            $table->dateTime('vital_signs_at')->nullable()->index();
            $table->dateTime('medicine_consulted_at')->nullable()->index();
            $table->string('outcome', 60)->nullable()->index();
            $table->string('remark')->nullable();
            $table->dateTime('encountered_at')->index();
            $table->dateTime('dismissed_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stays');
    }
}
