<?php namespace RomainMazB\Appointment;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'RomainMazB\Appointment\Components\Calendar' => 'calendar'
        ];
    }

    public function registerSettings()
    {
    }
}
