<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRomainmazbAppointmentHolidays extends Migration
{
    public function up()
    {
        Schema::create('romainmazb_appointment_holidays', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('romainmazb_appointment_holidays');
    }
}
