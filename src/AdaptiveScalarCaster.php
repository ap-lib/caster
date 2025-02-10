<?php declare(strict_types=1);

namespace AP\Caster;

use AP\Caster\Error\CastError;
use AP\Context\Context;

/**
 * Handles adaptive casting of scalar values to the expected type
 *
 * This caster:
 * - Converts numeric values to `int` or `float`
 * - Converts integers to `string`
 * - Converts `0` and `1` to `bool`
 * - Returns `true` if the value is successfully cast
 * - Returns `false` to indicate that casting should be skipped
 * - Never returns an array of errors: `array<CastError>`
 */
class AdaptiveScalarCaster implements CasterInterface
{
    /**
     * Attempts to cast a value to a flexible scalar type
     *
     * This method:
     * - Converts numeric values to `int` or `float`
     * - Converts integers to `string`
     * - Converts `0` and `1` to `bool`
     * - Returns `true` if casting is successful
     * - Returns `false` to skip casting
     *
     * @param string $expected The expected final type, see: `get_debug_type()`
     * @param mixed $el Reference to the value being cast
     * @param ?Context $context Context object providing metadata for casting
     * @return bool|array<CastError> `true` if successfully cast, `false` to skip
     */
    public function cast(
        string   $expected,
        mixed    &$el,
        ?Context $context = null,
    ): bool|array
    {
        switch ($expected) {
            case "int":
                if (is_numeric($el)) {
                    $el = (int)$el;
                    return true;
                }
                break;
            case "float":
                if (is_numeric($el)) {
                    $el = (float)$el;
                    return true;
                }
                break;
            case "string":
                if (is_int($el)) {
                    $el = (string)$el;
                    return true;
                }
                break;
            case "bool":
                if ($el === 0 || $el === 1) {
                    $el = (bool)$el;
                    return true;
                }
        }

        return false;
    }
}