<?php declare(strict_types=1);

namespace AP\Caster\Tests;

use AP\Caster\CasterInterface;
use AP\Context\Context;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class BaseCasterTextCase extends TestCase
{
    abstract protected function makeDefaultCaster(): CasterInterface;

    /**
     * @throws Throwable
     */
    public function assertCasterGood(
        string           $expectedType,
                         $expected,
                         $el,
        ?CasterInterface $caster = null,
        ?Context         $context = null
    ): void
    {
        $caster = $caster instanceof CasterInterface
            ? $caster
            : $this->makeDefaultCaster();

        $res = $caster->cast($expectedType, $el, $context);
        $this->assertTrue($res);
        $this->assertEquals($expected, $el);
    }

    /**
     * @throws Throwable
     */
    public function assertCasterError(
        string           $expectedType,
                         $el,
        ?CasterInterface $caster = null,
        ?Context         $context = null
    ): void
    {
        $caster = $caster instanceof CasterInterface
            ? $caster
            : $this->makeDefaultCaster();

        $expected = $el;
        $res      = $caster->cast($expectedType, $el, $context);
        $this->assertTrue(is_array($res) && count($res));

        // IMPORTANT. The caster mustn't modify the element if casting was unsuccessful
        $this->assertEquals($expected, $el);
    }

    /**
     * @throws Throwable
     */
    public function assertCasterSkip(
        string           $expectedType,
                         $el,
        ?CasterInterface $caster = null,
        ?Context         $context = null
    ): void
    {
        $caster = $caster instanceof CasterInterface
            ? $caster
            : $this->makeDefaultCaster();

        $expected = $el;
        $res      = $caster->cast($expectedType, $el, $context);
        $this->assertFalse($res);

        // IMPORTANT. The caster mustn't modify the element if casting was unsuccessful
        $this->assertEquals($expected, $el);
    }
}
