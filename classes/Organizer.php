<?php

namespace RomainMazB\Appointment\Classes;

use \Carbon\Carbon;
use \Carbon\CarbonInterval;
use \Carbon\CarbonPeriod;
use \Illuminate\Support\Collection;
use RomainMazB\Appointment\Classes\Interfaces\FilterInterface;

class Organizer
{
    public static $default_weeks_range = 3;

    public static $default_interval = '1 day';

    /**
     * Registered filter, all of this filter can be called from Organizer instance from their respective alias
     *
     * @var array
     */
    private static $registered_filters = [
        'whereDayOfWeek'     => \RomainMazB\Appointment\Classes\Filters\DayOfWeek::class,
        'forAppointmentType' => \RomainMazB\Appointment\Classes\Filters\ForAppointmentType::class
    ];

    // This filter will be applied on every getDates() call, to disable, use whenever() method
    private static $registered_default_filters = [
        'onlyOpeningHours'    => \RomainMazB\Appointment\Classes\Filters\OpeningHours::class,
        'notInHolidays'            => \RomainMazB\Appointment\Classes\Filters\NotInHolidays::class,
        'onlyAvailable'            =>  \RomainMazB\Appointment\Classes\Filters\Available::class
    ];

    /**
     * List of filters to apply, with default setup
     *
     * @var array
     */
    private $filters_to_apply = [];

    protected $dates;

    /**
     * Create and init an Organizer
     *
     * @param Carbon $from_date
     * @param CarbonInterval $interval
     * @param Carbon $to_date
     *
     * @return Organizer
     */
    public function __construct(Carbon $from_date = null, Carbon $to_date = null, CarbonInterval $interval = null)
    {
        $from_date = is_null($from_date) ? Carbon::today() : $from_date;

        $interval = is_null($interval) ? CarbonInterval::createFromDateString(self::$default_interval) : $interval;

        $to_date = is_null($to_date) ? $from_date->copy()->addWeeks(self::$default_weeks_range) : $to_date;

        // Create the initial Organizer
        $this->dates = new Collection([
            CarbonPeriod::create($from_date, $interval, $to_date)
                ->excludeEndDate()
        ]);

        return $this;
    }

    /**
     * Static alias for constructor
     *
     * @return Organizer
     */
    public static function init()
    {
        return new static;
    }

    /**
     * Apply the filters, the default filters, and return all the periods from the Organizer as CarbonPeriod objects
     *
     * @return Collection
     */
    public function getDates()
    {
        // Apply the default filters
        foreach(self::$registered_default_filters as $filter_alias => $filter_class) {
            $filter = new $filter_class($this->dates);
            $this->applyFilter($filter, null);
        }

        // Apply the user defined filters
        foreach($this->filters_to_apply as $filter_alias => $parameters) {
            $filter = new self::$registered_filters[$filter_alias]($this->dates);
            $this->applyFilter($filter, $parameters);
        }

        return $this->dates;
    }

    /**
     * Verify the integrity of the filter (exists and is valid) and apply it on the current dates
     *
     * @param String $filter_name
     * @param mixed $parameters
     * @return void
     */
    public function __call($filter_alias, $parameters)
    {
        // Check if the filter is correctly registered, if not: throw bad method exception
        if (!array_key_exists($filter_alias, self::$registered_filters)) {
            throw new \Exception(chr(27) . "[41mNo method ${default_filters_to_disable}" . chr(27) . "[0m");
        }

        $this->filters_to_apply[$filter_alias] = $parameters;

        return $this;
    }

    /**
     * Verify if dates is not empty then apply the filter on dates
     *
     * @param FilterInterface $filter
     * @param array $parameters
     * @return void
     */
    private function applyFilter(FilterInterface $filter, ...$parameters)
    {
        if(! $this->dates->isEmpty()) {
            $this->dates = $filter->applyParameters(...$parameters);
        }
    }

    /**
     * Register a custom filter
     *
     * To register a custom filter: pass the namespace and class name to $filter_class parameter
     * If $filter_alias_or_force_override is a string, it will be used as the alias, if it's a boolean, it will be used to override an existing filter
     * If you need to override a filter and provide a specific alias (which is not the class name), use the boolean $force_override
     *
     * @param String $filter_class
     * @param String|Bool $filter_alias_or_force_override
     * @param Bool $force_override
     *
     * @return void
     */
    public static function registerFilter(String $filter_class, Bool $is_default = false, $filter_alias_or_force_override = false, Bool $force_override = false)
    {
        try {
            // If the class doesn't exist: throw the php default error
            if (! class_exists($filter_class)) {
                new $filter_class();
            }

            // If an filter_alias were specified: use it, if not, use the classe name
            if (is_string($filter_alias_or_force_override)) {
                $filter_alias = $filter_alias_or_force_override;
            } else {
                // ProgrammerNameSpace\CustomPlugin\Filters\CustomFilter becomes customFilter() method
                $splitted_namespace = explode("\\", $filter_alias_or_force_override);
                $filter_alias = lcfirst(end($splitted_namespace));
            }
            $force_override = (is_bool($filter_alias_or_force_override)) ? $filter_alias_or_force_override : $force_override;

            // Determine in which array the user want to register the filter
            $array_of_filters = $is_default ? 'registered_default_filters' : 'registered_filters';
            // Throw an error if a filter with this alias is already registered and user didn't precise to override it
            if(array_key_exists($filter_alias, self::$$array_of_filters) && ! $force_override) {
                throw new \Exception(chr(27) . "[41mThe filter ${filter_alias} is already registered, to override it, pass the \$force_override parameter to true" . chr(27) . "[0m");
            }
        }  catch (\Exception $e) {
            die($e->getMessage());
        }

        self::$$array_of_filters[$filter_alias] = $filter_class;
    }

    /**
     * Disable one or many default filters, to disable one: passes the alias of the filter, with String or multiple aliases with Array
     * If null is passed, all the default filters will be disabled
     *
     * @param String|Array $default_filters_to_disable
     * @return void
     */
    public function withoutDefault($default_filters_to_disable = null) {
        try {
            $wrong_parameter_type = ! is_array($default_filters_to_disable) && ! is_string($default_filters_to_disable);
            if (isset($default_filters_to_disable) && $wrong_parameter_type) {
                throw new \Exception(chr(27) . "[41mWrong parameter type for \$default_filters_to_disable in whenever method call, need to be String or Array" . chr(27) . "[0m");
            }

            // Convert string as an array if needed
            if (is_string($default_filters_to_disable)) {
                $default_filters_to_disable = [$default_filters_to_disable];
            }

            foreach($default_filters_to_disable as $filter) {
                $index_of_filter = array_search($filter, self::$registered_default_filters);
                // Throw an exception if the filter is not registered.
                if(is_null($index_of_filter)) {
                    throw new \Exception(chr(27) . "[41mNo default filter ${filter} registered." . chr(27) . "[0m");
                }

                array_splice(self::$registered_default_filters, $index_of_filter, 1);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $this;
    }
}
