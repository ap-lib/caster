<?php declare(strict_types=1);

namespace AP\Caster\Tests;

use AP\Caster\CasterInterface;
use AP\Caster\JsonCaster;
use Throwable;

final class JsonCasterTest extends BaseCasterTextCase
{
    protected function makeDefaultCaster(): CasterInterface
    {
        return new JsonCaster();
    }

    /**
     * @throws Throwable
     */
    public function testBase(): void
    {
        $this->assertCasterGood(
            "array",
            ["hello" => "world"],
            '{"hello":"world"}'
        );
    }
}
