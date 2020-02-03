<?php

namespace RomainMazB\Appointment\Classes\Interfaces;

interface FilterInterface
{
    /**
     * Pass all the Collection's items to a filter
     *
     * Returns a filtered Collection
     *
     * @param Array $parameters
     * @return Collection
     */
    public function applyParameters($parameters);
}
