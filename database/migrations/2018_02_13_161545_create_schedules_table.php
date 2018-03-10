<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('calendar_id');
            $table->boolean('monday_available')->default(false)->nullable();
            $table->boolean('tuesday_available')->default(false)->nullable();
            $table->boolean('wednesday_available')->default(false)->nullable();
            $table->boolean('thursday_available')->default(false)->nullable();
            $table->boolean('friday_available')->default(false)->nullable();
            $table->boolean('saturday_available')->default(false)->nullable();
            $table->boolean('sunday_available')->default(false)->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('calendar_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
