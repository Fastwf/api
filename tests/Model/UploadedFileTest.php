<?php

namespace Fastwf\Tests\Model;

use PHPUnit\Framework\TestCase;
use Fastwf\Api\Model\UploadedFile;
use Psr\Http\Message\StreamInterface;

class UploadedFileTest extends TestCase
{

    private const PATH = __DIR__ . '/test.txt';

    private const CONTENT = "Hello world Fast Web Framework!";

    /**
     * The stream generated.
     *
     * @var StreamInterface
     */
    private $stream = null;

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::PATH, self::CONTENT);
    }

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

    /**
     * @covers Fastwf\Api\Model\UploadedFile
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testGetStream()
    {
        $file = new UploadedFile([
            'name' => \basename(self::PATH),
            "type" => "text/plain",
            "size" => strlen(self::CONTENT),
            "tmp_name" => self::PATH,
            "error" => 0
        ]);

        $stream = $file->getStream();
        $this->stream = $stream;

        $this->assertTrue($stream->isReadable());
        $this->assertEquals($file->getSize(), $stream->getSize());
        $this->assertEquals(self::CONTENT, $stream->getContents());

        $stream->close();
    }

    protected function tearDown(): void
    {
        if ($this->stream !== null)
        {
            try
            {
                $this->stream->close();
            }
            catch (\Throwable $e)
            {
                // Ignore the close error
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::PATH);
    }

}
