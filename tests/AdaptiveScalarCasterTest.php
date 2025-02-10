<?php declare(strict_types=1);

namespace AP\Caster\Tests;

use AP\Caster\AdaptiveScalarCaster;
use AP\Caster\CasterInterface;

final class AdaptiveScalarCasterTest extends BaseCasterTextCase
{
    protected function makeDefaultCaster(): CasterInterface
    {
        return new AdaptiveScalarCaster();
    }

    /**
     * @throws \Throwable
     */
    public function testString(): void
    {
        $this->assertCasterGood("string", "1", 1);

        $this->assertCasterSkip("string", 3.14);
        $this->assertCasterSkip("string", null);
        $this->assertCasterSkip("string", true);
        $this->assertCasterSkip("string", false);
        $this->assertCasterSkip("string", ["hello" => "world"]);
    }

    public function testInt(): void
    {
        $this->assertCasterGood("int", 1, "1");
        $this->assertCasterGood("int", 1, "1.13");
        $this->assertCasterGood("int", 0, "0");
        $this->assertCasterGood("int", 0, "0.01");
        $this->assertCasterGood("int", 0, "0.0");
        $this->assertCasterGood("int", -1, "-1");
        $this->assertCasterGood("int", -1, "-1.34");
        $this->assertCasterGood("int", -1, -1.13);
        $this->assertCasterGood("int", 1, 1.13);
        $this->assertCasterGood("int", 0, 0.0);

        $this->assertCasterSkip("int", "one");
        $this->assertCasterSkip("int", true);
        $this->assertCasterSkip("int", false);
        $this->assertCasterSkip("int", null);
        $this->assertCasterSkip("int", ["hello" => "world"]);
    }

    public function testFloat(): void
    {
        $this->assertCasterGood("float", 1.0, "1");
        $this->assertCasterGood("float", 1.13, "1.13");
        $this->assertCasterGood("float", 0.0, "0");
        $this->assertCasterGood("float", 0.01, "0.01");
        $this->assertCasterGood("float", 0.0, "0.0");
        $this->assertCasterGood("float", -1.0, "-1");
        $this->assertCasterGood("float", -1.34, "-1.34");
        $this->assertCasterGood("float", -1.0, -1);
        $this->assertCasterGood("float", 1.0, 1);
        $this->assertCasterGood("float", 0.0, 0);

        $this->assertCasterSkip("float", "one");
        $this->assertCasterSkip("float", true);
        $this->assertCasterSkip("float", false);
        $this->assertCasterSkip("float", null);
        $this->assertCasterSkip("float", ["hello" => "world"]);
    }

    public function testBool(): void
    {
        $this->assertCasterGood("bool", true, 1);
        $this->assertCasterGood("bool", false, 0);

        $this->assertCasterSkip("bool", "true");
        $this->assertCasterSkip("bool", "false");
        $this->assertCasterSkip("bool", 1.0);
        $this->assertCasterSkip("bool", 0.0);
        $this->assertCasterSkip("bool", null);
        $this->assertCasterSkip("string", ["true" => "false"]);
    }
}
