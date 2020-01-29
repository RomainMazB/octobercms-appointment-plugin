<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRomainmazbAppointmentOpeningHours extends Migration
{
    public function up()
    {
        Schema::create('romainmazb_appointment_opening_hours', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->smallInteger('day_of_the_week');
            $table->time('open_at');
            $table->time('close_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('romainmazb_appointment_opening_hours');
    }
}
