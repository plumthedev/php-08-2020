<?php

namespace App\Services\Movies\Contracts;

interface Processor
{
    /**
     * Get processor cache key.
     *
     * @return string
     */
    public function getCacheKey(): string;

    /**
     * Get processed movies.
     *
     * @return array
     */
    public function getResults(): array;

    /**
     * Get processed movies titles.
     *
     * @return array
     */
    public function getTitles(): array;
}
