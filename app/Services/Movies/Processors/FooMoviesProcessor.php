<?php

namespace App\Services\Movies\Processors;

use App\Services\Movies\AbstractProcessor;
use External\Foo\Movies\MovieService;
use Illuminate\Cache\Repository;

class FooMoviesProcessor extends AbstractProcessor
{
    /**
     * Processor cache key.
     *
     * @var string
     */
    protected $cacheKey = 'foo-movies';

    /**
     * Foo movies service.
     *
     * @var \External\Foo\Movies\MovieService
     */
    protected $movieService;

    /**
     * Create new instance of movies processor.
     *
     * @param \Illuminate\Cache\Repository      $cache
     * @param \External\Foo\Movies\MovieService $movieService
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
     * @throws \External\Foo\Exceptions\ServiceUnavailableException
     */
    public function getTitles(): array
    {
        return $this->movieService->getTitles();
    }
}
