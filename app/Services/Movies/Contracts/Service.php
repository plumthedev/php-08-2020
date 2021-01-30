<?php

namespace App\Services\Movies\Contracts;

interface Service
{
    /**
     * Get service results.
     *
     * @return array
     */
    public function getResults(): array;

    /**
     * Run service to collect results via processors.
     *
     * @return \App\Services\Movies\Contracts\Service
     */
    public function process(): Service;
}
