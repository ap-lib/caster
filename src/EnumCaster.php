<?php declare(strict_types=1);

namespace AP\Caster;

use AP\Context\Context;
use AP\ErrorNode\Error;
use Throwable;

/**
 * Handles casting values to enum types if possible
 *
 * This caster:
 * - Supports PHP backed enums that implement the `tryFrom` method
 * - Only allows casting from `int` and `string` types
 * - Returns `true` if the value is successfully cast
 * - Returns a non-empty array of errors if casting fails
 * - Returns `false` to indicate that casting should be skipped
 */
class EnumCaster implements CasterInterface
{
    const array ALLOWED_FOR_ENUM_HASHMAP = [
        "int"    => true,
        "string" => true,
    ];

    /**
     * By performance reason, skip it expected is one of this list immediately using must fast hashmap search
     */
    const array SKIP_EXPECTED_HASHMAP = [
        "array"  => true,
        "int"    => true,
        "float"  => true,
        "string" => true,
        "bool"   => true,
        "null"   => true,
    ];

    /**
     * Attempts to cast a value to an enum type
     *
     * If the expected type is an enum and the value is a valid backing type,
     * the method attempts to cast using the `tryFrom` method.
     * If successful, the original value is replaced with the corresponding enum case
     *
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
        if (key_exists($expected, self::SKIP_EXPECTED_HASHMAP)) {
            return false;
        }

        if (!enum_exists($expected)) {
            return false;
        }

        $actual = get_debug_type($el);
        if (isset(self::ALLOWED_FOR_ENUM_HASHMAP[$actual])) {
            if (!method_exists($expected, "from")) {
                return false;
            }
            try {
                $el = $expected::from($el);
                return true;
            } catch (Throwable) {
            }
        }
        $allowed_values = [];
        foreach ($expected::cases() as $case) {
            $allowed_values[] = $case->value;
        }
        return [
            new Error("allowed values: " . implode(", ", $allowed_values))
        ];
    }
}