<?php

namespace Fastwf\Tests\Model;

use PHPUnit\Framework\TestCase;
use Fastwf\Api\Model\UploadedFile;

class UploadedFileTest extends TestCase
{

    /**
     * @covers Fastwf\Api\Model\UploadedFile
     */
    public function testUploadedFileClass() {
        $file = new UploadedFile([
            "name" => "head.png",
            "type" => "image/png",
            "size" => 3652,
            "tmp_name" => "/tmp/phpWW5yhj",
            "error" => 0
        ]);

        $this->assertEquals("head.png", $file->getClientFilename());
        $this->assertEquals("image/png", $file->getClientMediaType());
        $this->assertEquals(3652, $file->getSize());
        $this->assertEquals("/tmp/phpWW5yhj", $file->getPath());
        $this->assertEquals(0, $file->getError());
    }

}
