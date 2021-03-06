<?php

namespace Fastwf\Tests\Utils\Files;

use PHPUnit\Framework\TestCase;
use Fastwf\Api\Model\UploadedFile;
use Fastwf\Api\Utils\Files\UploadedFileUtil;

class UploadedFileUtilTest extends TestCase
{
    
    /**
     * @covers Fastwf\Api\Model\UploadedFile
     * @covers Fastwf\Api\Utils\Files\UploadedFileUtil
     */
    function testFromSuperGlobal() {
        $this->assertEquals(
            [
                "main" => new UploadedFile([
                    "name" => "head.png",
                    "type" => "image/png",
                    "size" => 3652,
                    "tmp_name" => "/tmp/phpWW5yhj",
                    "error" => 0
                ]),
                "secondary" => [
                    "index" => new UploadedFile([
                        "name" => "index.svg",
                        "type" => "image/svg+xml",
                        "size" => 4724,
                        "tmp_name" => "/tmp/phpHwoHYj",
                        "error" => 0
                    ]),
                    "appendix" => new UploadedFile([
                        "name" => "appendix.jpg",
                        "type" => "image/jpeg",
                        "size" => 283926,
                        "tmp_name" => "/tmp/phpvIMFHj",
                        "error" => 0
                    ])
                ],            
                "others" => [
                    "0" => new UploadedFile([
                        "name" => "otherA.jpg",
                        "type" => "image/jpeg",
                        "size" => 283926,
                        "tmp_name" => "/tmp/php6I9Xil",
                        "error" => 0
                    ]),            
                    "1" => new UploadedFile([
                            "name" => "otherB.jpg",
                            "type" => "image/jpeg",
                            "size" => 283926,
                            "tmp_name" => "/tmp/phppftaJj",
                            "error" => 0
                    ])
                ]
            ],
            UploadedFileUtil::fromSuperGlobal([
                "main" => [
                    "name" => "head.png",
                    "type" => "image/png",
                    "tmp_name" => "/tmp/phpWW5yhj",
                    "error" => 0,
                    "size" => 3652
                ],
                "secondary" => [
                    "name" => [
                        "index" => "index.svg",
                        "appendix" => "appendix.jpg"
                    ],
                    "type" => [
                        "index" => "image/svg+xml",
                        "appendix" => "image/jpeg"
                    ],
                    "tmp_name" => [
                        "index" => "/tmp/phpHwoHYj",
                        "appendix" => "/tmp/phpvIMFHj"
                    ],
                    "error" => [
                        "index" => 0,
                        "appendix" => 0
                    ],
                    "size" => [
                        "index" => 4724,
                        "appendix" => 283926
                    ]
                ],
                "others" => [
                    "name" => [
                        0 => "otherA.jpg",
                        1 => "otherB.jpg"
                    ],
                    "type" => [
                        0 => "image/jpeg",
                        1 => "image/jpeg"
                    ],
                    "tmp_name" => [
                        0 => "/tmp/php6I9Xil",
                        1 => "/tmp/phppftaJj"
                    ],
                    "error" => [
                        0 => 0,
                        1 => 0
                    ],
                    "size" => [
                        0 => 283926,
                        1 => 283926
                    ]
                ]
            ])
        );
    }

}
