<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Api\Exceptions\KeyError;

class ArrayUtilTest extends TestCase {

    /**
     * @covers Fastwf\Api\Utils\ArrayUtil
     */
    public function testGet() {
        $this->assertEquals('foo', ArrayUtil::get(['bar' => 'foo'], 'bar'));
    }

    /**
     * @covers Fastwf\Api\Utils\ArrayUtil
     */
    public function testGetError() {
        $this->expectException(KeyError::class);

        ArrayUtil::get(['bar' => 'foo'], 'foo');
    }

    /**
     * @covers Fastwf\Api\Utils\ArrayUtil
     */
    public function testGetSafe() {
        $this->assertEquals('foo', ArrayUtil::getSafe(['bar' => 'foo'], 'bar'));
    }

    /**
     * @covers Fastwf\Api\Utils\ArrayUtil
     */
    public function testGetSafeDefault() {
        $this->assertEquals('bar', ArrayUtil::getSafe(['bar' => 'foo'], 'foo', 'bar'));
    }

}
