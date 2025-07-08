<?php declare(strict_types=1);

namespace AP\Caster;

use AP\Context\Context;
use AP\ErrorNode\Error;
use JsonException;

/**
 * Caster that converts JSON strings to PHP arrays when the expected type is `array`
 *
 * This caster:
 * - Returns `true` if the value is successfully cast
 * - Returns a non-empty array of errors if casting fails
 * - Returns `false` to indicate that casting should be skipped
 */
readonly class JsonCaster implements CasterInterface
{
    /**
     * @param int $depth [optional] <p>
     *  User specified recursion depth.
     *  </p>
     * @param int $flags [optional] <p>
     *  Bitmask of JSON decode options:<br/>
     *  {@see JSON_BIGINT_AS_STRING} decodes large integers as their original string value.<br/>
     *  {@see JSON_INVALID_UTF8_IGNORE} ignores invalid UTF-8 characters,<br/>
     *  {@see JSON_INVALID_UTF8_SUBSTITUTE} converts invalid UTF-8 characters to \0xfffd,<br/>
     *  </p>
     */
    public function __construct(
        public int $depth = 512,
        public int $flags = 0,
    )
    {
    }

    /**
     * @param string $expected The expected final type, see: `get_debug_type()`
     * @param mixed $el Reference to the value being cast
     * @param ?Context $context Context object providing metadata for casting
     * @return bool|array<Error> `true` if successfully cast, `false` to skip, non-empty array if casting fails
     */
    public function cast(
        string   $expected,
        mixed    &$el,
        ?Context $context = null,
    ): bool|array
    {
        if ($expected == "array" && is_string($el)) {
            try {
                $el = json_decode(
                    $el,
                    true,
                    $this->depth,
                    $this->flags | JSON_THROW_ON_ERROR
                );
                return true;
            } catch (JsonException $e) {
                return [new Error($e->getMessage())];
            }
        }
        return false;
    }
}