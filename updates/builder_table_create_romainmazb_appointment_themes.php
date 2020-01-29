<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRomainmazbAppointmentAppointmentTypes extends Migration
{
    public function up()
    {
        Schema::create('romainmazb_appointment_appointment_types', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('name');
            $table->text('description');
            $table->time('duration');
            $table->decimal('price', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('romainmazb_appointment_appointment_types');
    }
}
