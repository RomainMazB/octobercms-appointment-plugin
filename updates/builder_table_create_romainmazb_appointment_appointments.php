<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRomainmazbAppointmentAppointments extends Migration
{
    public function up()
    {
        Schema::create('romainmazb_appointment_appointments', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->text('message')->nullable();
            $table->integer('appointment_type_id');
            $table->dateTime('datetime');
            $table->string('phone', 10)->nullable(false)->unsigned(false)->default(null);
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('romainmazb_appointment_appointments');
    }
}
