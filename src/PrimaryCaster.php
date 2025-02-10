<?php declare(strict_types=1);

namespace AP\Caster;

use AP\Caster\Error\CastError;
use AP\Caster\Error\UnexpectedType;
use AP\Context\Context;
use Throwable;
use UnexpectedValueException;

/**
 * Handles basic casting logic by enforcing exact type matches and delegating to additional casters if necessary.
 *
 * This caster:
 * - Approves values that already match the expected type
 * - Delegates casting to a list of additional casters if a direct match isn't found
 * - Always returns either `true`: successfully cast or a non-empty array of `CastError` instances.
 * - Never returns `false` skipping isn't allowed
 */
readonly class PrimaryCaster implements CasterInterface
{
    /**
     * @var array<CasterInterface> casters to attempt if direct type matching fails
     */
    protected array $casters;

    /**
     * Initializes the primary caster with an optional list of additional casters.
     *
     * @param array<CasterInterface> $casters An array of additional casters to handle complex type conversions.
     * @throws UnexpectedValueException If any, provided caster doesn't implement SingleCasterInterface.
     */
    public function __construct(array $casters = [])
    {
        foreach ($casters as $caster) {
            if (!($caster instanceof CasterInterface)) {
                throw new UnexpectedValueException("all casters must implement " . CasterInterface::class);
            }
        }
        $this->casters = $casters;
    }

    /**
     * Attempts to cast a value to the expected type.
     *
     * This method:
     * - Approves values that already match `$expected`
     * - Iterates through additional casters if an exact match isn't found
     * - Always returns either `true` or a **non-empty** array of errors
     * - Never returns `false` - skipping isn't allowed
     *
     * @param string $expected The expected final type, see: `get_debug_type()`
     * @param mixed   &$el Reference to the original data, which may be modified but only if casting succeeds
     * @param ?Context $context Context object providing metadata and settings for casting
     *
     * @return true|array<CastError> `true` if successfully cast, or a **non-empty** array of errors if casting fails
     * @throws Throwable If a fatal error occurs, that's unrelated to input data validation
     */
    public function cast(
        string   $expected,
        mixed    &$el,
        ?Context $context = null,
    ): true|array
    {
        $actual = get_debug_type($el);
        if ($expected == $actual) { // 100% matching data types
            return true;
        }

        foreach ($this->casters as $caster) {
            $res = $caster->cast(
                $expected,
                $el,
                $context,
            );
            if ($res === true) {
                return true;
            }
            if (is_array($res) && count($res)) {
                return $res;
            }
        }

        return [
            new UnexpectedType(
                $expected,
                $actual,
            )
        ];
    }
}