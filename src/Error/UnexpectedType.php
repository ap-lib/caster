<?php

namespace AP\Caster\Error;

/**
 * Exception thrown when a value's type does not match the expected type during casting operations
 *
 * This exception:
 * - Extends the CastError base class
 * - Indicates that a value of an unexpected type was encountered
 */
class UnexpectedType extends CastError
{
    /**
     * @param string $expected The expected data type
     * @param string $actual The actual data type encountered
     * @param array<string> $path The path to the value within the data structure
     */
    public function __construct(
        readonly public string $expected,
        readonly public string $actual,
        array                  $path = [],
    )
    {
        parent::__construct(
            "Unexpected date type, expected `$expected`, actual `$actual`",
            $path,
        );
    }
}