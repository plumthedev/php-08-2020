<?php

namespace App\Services\Movies\Processors;

use App\Services\Movies\AbstractProcessor;
use External\Baz\Movies\MovieService;
use Illuminate\Cache\Repository;
use Illuminate\Support\Arr;

class BazMoviesProcessor extends AbstractProcessor
{
    /**
     * Processor cache key.
     *
     * @var string
     */
    protected $cacheKey = 'baz-movies';

    /**
     * Baz movies service.
     *
     * @var \External\Baz\Movies\MovieService
     */
    protected $movieService;

    /**
     * Create new instance of movies processor.
     *
     * @param \Illuminate\Cache\Repository      $cache
     * @param \External\Baz\Movies\MovieService $movieService
     */
    public function __construct(Repository $cache, MovieService $movieService)
    {
        parent::__construct($cache);
        $this->movieService = $movieService;
    }

    /**
     * Get processed movies titles.
     *
     * @return array
     * @throws \External\Baz\Exceptions\ServiceUnavailableException
     */
    public function getTitles(): array
    {
        $titles = $this->movieService->getTitles();
        $titles = Arr::get($titles, 'titles', []);

        return $titles;
    }
}
