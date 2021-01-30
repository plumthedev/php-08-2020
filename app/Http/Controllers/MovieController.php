<?php

namespace App\Http\Controllers;

use App\Services\Movies\Contracts\Service;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    /**
     * Movies service.
     *
     * @var \App\Services\Movies\Contracts\Service
     */
    protected $moviesService;

    /**
     * Movie controller constructor.
     *
     * @param \App\Services\Movies\Contracts\Service $moviesService
     */
    public function __construct(Service $moviesService)
    {
        $this->moviesService = $moviesService;
    }

    /**
     * Get services movies titles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTitles(): JsonResponse
    {
        try {
            return response()->json(
                $this->moviesService->getResults()
            );
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'failure',
            ]);
        }
    }
}
