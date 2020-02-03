<?php

namespace RomainMazB\Appointment\Classes\Filters;

use \Illuminate\Support\Collection;

abstract class Filter extends Collection
{
    protected $initial_collection;

    protected $filtered_collection;

    /**
     * Saves the initial state of the collection
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
        $this->initial_collection = clone $collection;
        $this->filtered_collection =  new Collection();

        return $this;
    }
}
