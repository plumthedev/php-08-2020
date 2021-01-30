<?php

namespace App\Services\Movies\Processors;

use App\Services\Movies\AbstractProcessor;
use External\Bar\Movies\MovieService;
use Illuminate\Cache\Repository;
use Illuminate\Support\Arr;

class BarMoviesProcessor extends AbstractProcessor
{
    /**
     * Processor cache key.
     *
     * @var string
     */
    protected $cacheKey = 'bar-movies';

    /**
     * Bar movies service.
     *
     * @var \External\Bar\Movies\MovieService
     */
    protected $movieService;

    /**
     * Create new instance of movies processor.
     *
     * @param \Illuminate\Cache\Repository      $cache
     * @param \External\Bar\Movies\MovieService $movieService
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
     * @throws \External\Bar\Exceptions\ServiceUnavailableException
     */
    public function getTitles(): array
    {
        $titles = $this->movieService->getTitles();
        $titles = Arr::get($titles, 'titles', []);
        $titles = Arr::pluck($titles, 'title');

        return $titles;
    }
}
