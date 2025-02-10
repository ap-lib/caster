<?php declare(strict_types=1);

namespace AP\Caster\Tests;

use AP\Caster\AdaptiveScalarCaster;
use AP\Caster\EnumCaster;
use AP\Caster\Error\UnexpectedType;
use AP\Caster\PrimaryCaster;
use PHPUnit\Framework\TestCase;
use Throwable;

final class ReadmeTest extends TestCase
{

    /**
     * @throws Throwable
     */
    public function testMatchingData(): void
    {
        $caster = new PrimaryCaster([
            new AdaptiveScalarCaster(),
            new EnumCaster(),
        ]);

        // matching data-type
        $data   = "hello world";
        $result = $caster->cast("string", $data);

        $this->assertTrue($result);
        $this->assertEquals("hello world", $data);

        // $result = true
        // $data = "hello world"
    }

    /**
     * @return void
     * @throws Throwable
     * @see AdaptiveScalarCaster for more details
     *
     */
    public function testAdaptiveScalar(): void
    {
        $caster = new PrimaryCaster([
            new AdaptiveScalarCaster(),
            new EnumCaster(),
        ]);

        $data   = "1200";
        $result = $caster->cast("int", $data);

        $this->assertTrue($result);
        $this->assertEquals(1200, $data);

        // $result = true
        // $data = 1200
    }

    public function testError(): void
    {
        $caster = new PrimaryCaster([
            new AdaptiveScalarCaster(),
            new EnumCaster(),
        ]);

        $data   = ["hello" => "world"];
        $result = $caster->cast("int", $data);

        $this->assertTrue(is_array($result));
        $this->assertEquals(
            [
                new UnexpectedType(
                    "int",
                    "array",
                    []
                )
            ],
            $result
        );

        // $result = true
        // $data = ["hello" => "world"]
        /*
            result = [
              \AP\Caster\Error\UnexpectedType::__set_state([
                 'message' => 'Unexpected date type, expected `int`, actual `array`',
                 'path' => [],
                 'expected' => 'int',
                 'actual' => 'array',
              ]),
            ]
        */
    }
}
