<?php declare(strict_types=1);

namespace AP\Caster\Tests;

use AP\Caster\CasterInterface;
use AP\Caster\EnumCaster;
use AP\Caster\Tests\Objects\OneTwoThreeExampleEnumInt;
use AP\Caster\Tests\Objects\OneTwoThreeExampleEnumString;
use Throwable;

final class EnumCasterTest extends BaseCasterTextCase
{
    protected function makeDefaultCaster(): CasterInterface
    {
        return new EnumCaster();
    }

    /**
     * @throws Throwable
     */
    public function testIntVersion(): void
    {
        $this->assertCasterGood(
            OneTwoThreeExampleEnumInt::class,
            OneTwoThreeExampleEnumInt::One,
            OneTwoThreeExampleEnumInt::One->value
        );

        $this->assertCasterGood(
            OneTwoThreeExampleEnumInt::class,
            OneTwoThreeExampleEnumInt::Two,
            OneTwoThreeExampleEnumInt::Two->value
        );

        $this->assertCasterGood(
            OneTwoThreeExampleEnumInt::class,
            OneTwoThreeExampleEnumInt::Three,
            OneTwoThreeExampleEnumInt::Three->value
        );

        // Scalar strict: doesn't allow (string) "1" if the value is (int) 1
        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, "1");
        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, "2");
        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, "3");

        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, "one");
        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, true);
        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, null);
        $this->assertCasterError(OneTwoThreeExampleEnumInt::class, [1]);
    }

    /**
     * @throws Throwable
     */
    public function testStringVersion(): void
    {
        $this->assertCasterGood(
            OneTwoThreeExampleEnumString::class,
            OneTwoThreeExampleEnumString::One,
            OneTwoThreeExampleEnumString::One->value
        );

        $this->assertCasterGood(
            OneTwoThreeExampleEnumString::class,
            OneTwoThreeExampleEnumString::Two,
            OneTwoThreeExampleEnumString::Two->value
        );

        $this->assertCasterGood(
            OneTwoThreeExampleEnumString::class,
            OneTwoThreeExampleEnumString::Three,
            OneTwoThreeExampleEnumString::Three->value
        );

        $this->assertCasterError(OneTwoThreeExampleEnumString::class, 1);
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, 2);
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, 3);
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, "1");
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, "2");
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, "3");
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, "hello");
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, true);
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, null);
        $this->assertCasterError(OneTwoThreeExampleEnumString::class, [1]);
    }
}
