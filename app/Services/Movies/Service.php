<?php

namespace App\Services\Movies;

use App\Services\Movies\Contracts\Processor;
use App\Services\Movies\Contracts\Service as ServiceContract;
use App\Services\Movies\Exceptions\InvalidProcessorException;
use Illuminate\Config\Repository;

class Service implements ServiceContract
{
    /**
     * Service configuration.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Is service processed movies.
     *
     * @var bool
     */
    protected $processed = false;

    /**
     * Service results.
     *
     * @var array
     */
    protected $results = [];

    /**
     * Create new service instance.
     *
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Get service results.
     *
     * @return array
     */
    public function getResults(): array
    {
        if (! $this->processed) {
            $this->process();
        }

        return $this->results;
    }

    /**
     * Run service to collect results via processors.
     *
     * @return \App\Services\Movies\Contracts\Service
     */
    public function process(): ServiceContract
    {
        foreach ($this->getProcessors() as $processor) {
            $this->results = array_merge(
                $processor->getResults(),
                $this->results
            );
        }

        $this->processed = true;
        return $this;
    }

    /**
     * Get movies processors.
     *
     * @return array<\App\Services\Movies\AbstractProcessor>
     */
    protected function getProcessors(): array
    {
        return collect(
            $this->config->get('processors', [])
        )->map(function (string $processorClassname) {
            $processor = app()->make($processorClassname);

            if (! is_a($processor, Processor::class)) {
                throw InvalidProcessorException::byInvalidClassname(
                    get_class($processor)
                );
            }

            return $processor;
        })->toArray();
    }
}
