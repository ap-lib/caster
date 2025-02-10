<?php declare(strict_types=1);

namespace AP\Caster;

use AP\Caster\Error\CastError;
use AP\Context\Context;
use Throwable;

/**
 * Defines the contract for casters that modify values during serialization or deserialization.
 *
 * Implementations should prioritize performance and efficiency, ensuring minimal overhead
 * when processing large datasets. The caster is responsible for transforming values
 * according to the expected type while handling possible errors.
 */
interface CasterInterface
{
    /**
     * Attempts to cast a given value to the expected type.
     *
     * Implementations should:
     *  - Return `false` to skip casting.
     *  - Return `true` if casting was successful.
     *  - Return a non-empty array of `CastError` instances if casting failed.
     *
     * @param string $expected The expected final type, see: `get_debug_type()`
     * @param mixed    &$el The value to be cast, passed by reference
     * @param ?Context $context Context object providing metadata and settings for casting
     *
     * @return bool|array<CastError> `false` to skip, `true` if successfully casted,
     *                               or a non-empty array of errors if casting failed.
     * @throws Throwable If a fatal error occurs, that's unrelated to input validation.
     */
    public function cast(
        string   $expected,
        mixed    &$el,
        ?Context $context = null,
    ): bool|array;
}