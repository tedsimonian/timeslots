<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->date('day');
            $table->string('start');
            $table->string('end');
            $table->string('title');
            $table->string('color');
            $table->integer('color_id');
            $table->text('description');
            $table->timestamps();

            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_events');
    }
}
