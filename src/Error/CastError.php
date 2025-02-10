<?php

namespace AP\Caster\Error;

/**
 * Base class for errors encountered during casting operations
 *
 * This class:
 * - Stores an error message
 * - Associates the error with a specific path in the data structure
 * - Serves as a parent class for more specific casting-related errors
 */
class CastError
{
    /**
     * @param string $message The error message
     * @param array<string> $path The path associated with the error
     */
    public function __construct(
        readonly public string $message,
        public array           $path = [],
    )
    {
    }
}
