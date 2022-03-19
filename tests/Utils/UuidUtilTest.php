<?php

namespace Fastwf\Tests\Utils;

use Fastwf\Api\Utils\UuidUtil;
use PHPUnit\Framework\TestCase;


class UuidUtilTest extends TestCase {

    /**
     * @covers Fastwf\Api\Utils\UuidUtil
     */
    public function testIsUuid() {
        $this->assertTrue(UuidUtil::isUuid('01234567-89ab-cdef-a456-426614174000'));
        $this->assertFalse(UuidUtil::isUuid('01234567-89ab-cdef-a56-426614174000'));
        $this->assertFalse(UuidUtil::isUuid('123e4567-e89b-12d3-a456-426614174000-000'));
        $this->assertFalse(UuidUtil::isUuid('123e4567-e89b-12d3-a456-426614174000-'));
        $this->assertFalse(UuidUtil::isUuid('123e4567-e89b-12d3-a456'));
        $this->assertFalse(UuidUtil::isUuid('123e4567-e89b-12d3-'));
        $this->assertFalse(UuidUtil::isUuid('01234567-89ab-cdef-a456-mlkjuiop'));
    }

}
