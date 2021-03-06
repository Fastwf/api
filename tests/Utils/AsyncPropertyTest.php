<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;

use Fastwf\Api\Utils\AsyncProperty;

class AsyncPropertyTest extends TestCase {

    /**
     * @covers Fastwf\Api\Utils\AsyncProperty
     */
    public function testValue() {
        $prop = new AsyncProperty("test");

        $this->assertEquals("test", $prop->get());
    }

    /**
     * @covers Fastwf\Api\Utils\AsyncProperty
     */
    public function testValueNull() {
        $prop = new AsyncProperty(null);

        $this->assertNull($prop->get());
    }

    /**
     * @covers Fastwf\Api\Utils\AsyncProperty
     */
    public function testValueInvoke() {
        $getter = new AsyncProperty("invoke");

        $this->assertEquals("invoke", $getter());
    }

    /**
     * @covers Fastwf\Api\Utils\AsyncProperty
     */
    public function testValueFactory() {
        $prop = new AsyncProperty(function () { return "async"; });

        $this->assertEquals("async", $prop->get());
    }

    /**
     * @covers Fastwf\Api\Utils\AsyncProperty
     */
    public function testValueFactoryWithParameters() {
        $prop = new AsyncProperty(function ($value) { return $value; });

        $this->assertEquals("async", $prop->get("async"));
    }

}
