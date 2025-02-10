<?php declare(strict_types=1);

namespace AP\Caster\Tests;

use AP\Caster\CasterInterface;
use AP\Caster\PrimaryCaster;
use Exception;
use Throwable;

final class PrimaryCasterTest extends BaseCasterTextCase
{
    protected function makeDefaultCaster(): CasterInterface
    {
        return new PrimaryCaster();
    }

    /**
     * @throws Throwable
     */
    public function testSimple(): void
    {
        $this->assertCasterGood("string", "hello", "hello");
        $this->assertCasterGood("int", 1, 1);
        $this->assertCasterGood("float", 3.14, 3.14);
        $this->assertCasterGood("bool", true, true);
        $this->assertCasterGood("bool", false, false);
        $this->assertCasterGood("null", null, null);
        $this->assertCasterGood("array", [], []);
        $this->assertCasterGood(Exception::class, new Exception(), new Exception());
    }
}
