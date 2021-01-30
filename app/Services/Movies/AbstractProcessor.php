<?php

namespace App\Services\Movies;

use App\Services\Movies\Contracts\Processor;
use Illuminate\Cache\Repository;

abstract class AbstractProcessor implements Processor
{
    /**
     * Application cache repository.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * Processor cache key.
     *
     * @var string
     */
    protected $cacheKey = 'movies-processor';

    /**
     * Create new abstract processor instance.
     *
     * @param \Illuminate\Cache\Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get processor cache key.
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * Get processed results.
     *
     * @return array
     */
    public function getResults(): array
    {
        return $this->cache->remember($this->getCacheKey(), now()->addDay(), function () {
            return $this->getTitles();
        });
    }
}
