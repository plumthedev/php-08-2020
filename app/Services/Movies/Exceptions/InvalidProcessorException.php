<?php

namespace App\Services\Movies\Exceptions;

use App\Services\Movies\Contracts\Processor;

class InvalidProcessorException extends \LogicException
{
    /**
     * Create new instance by invalid classname provided.
     *
     * @param string $classname
     *
     * @return static
     */
    public static function byInvalidClassname(string $classname): self
    {
        return new self(
            sprintf(
                'Invalid processor class provided [%s], must implements [%s]',
                $classname, Processor::class
            )
        );
    }
}
