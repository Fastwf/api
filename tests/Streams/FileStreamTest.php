<?php

namespace Fastwf\Tests\Streams;

use PHPUnit\Framework\TestCase;
use Fastwf\Api\Streams\FileStream;
use Psr\Http\Message\StreamInterface;
use Fastwf\Api\Exceptions\IOException;

class FileStreamTest extends TestCase
{

    private const PATH = __DIR__ . '/test.txt';

    /**
     * The stream to close.
     *
     * @var StreamInterface|null
     */
    private $stream;

    /**
     * The resource to close.
     *
     * @var resource|null
     */
    private $resource;

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testGetSize()
    {
        $this->stream = new FileStream('undefined.txt', 'r', 1024);

        $this->assertEquals(
            1024,
            $this->stream->getSize(),
        );
    }

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testGetSizeStat()
    {
        $this->stream = new FileStream(__FILE__, 'r');

        $this->assertEquals(
            \stat(__FILE__)['size'],
            $this->stream->getSize(),
        );
    }

    /// READ

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testToString()
    {
        $this->stream = new FileStream(__FILE__, 'r');
        $this->stream->rewind();

        $this->stream->seek(50);

        $this->assertEquals(
            file_get_contents(__FILE__),
            (string) $this->stream,
        );
    }

    /**
     * @covers Fastwf\Api\Streams\FileStream
     * @covers Fastwf\Api\Utils\ArrayUtil
     */
    public function testSeekTell()
    {
        $this->stream = new FileStream(__FILE__, 'r');
        $this->stream->rewind();

        $this->assertTrue($this->stream->isSeekable());

        $this->stream->seek(50);

        $this->assertEquals(
            50,
            $this->stream->tell(),
        );
    }

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testMetadata()
    {
        $this->stream = new FileStream(__FILE__, 'r');
        $this->stream->rewind();

        $this->resource = \fopen(__FILE__, 'r');

        $this->assertEquals(
            \stream_get_meta_data($this->resource),
            $this->stream->getMetadata(),
        );
    }

    /**
     * @covers Fastwf\Api\Streams\FileStream
     * @covers Fastwf\Api\Utils\ArrayUtil
     */
    public function testMetadataKey()
    {
        $this->stream = new FileStream(__FILE__, 'r');
        $this->stream->rewind();

        $this->assertEquals(
            'r',
            $this->stream->getMetadata('mode')
        );
    }

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testDetach()
    {
        $this->stream = new FileStream(__FILE__, 'r');
        $this->stream->rewind();

        $this->assertTrue($this->stream->isReadable());

        $this->resource = $this->stream->detach();

        $this->assertFalse($this->stream->isReadable());
    }

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testWhenOpen()
    {
        $this->expectException(IOException::class);

        $this->stream = new FileStream(__FILE__, 'r');
        $this->stream->read(1024);
    }

    /// WRITE

    /**
     * @covers Fastwf\Api\Streams\FileStream
     */
    public function testWToString()
    {
        $this->stream = new FileStream(self::PATH, 'w');

        $this->stream->write('test');

        $this->assertEquals('', (string) $this->stream);
    }

    protected function tearDown(): void
    {
        if ($this->stream !== null)
        {
            try
            {
                $this->stream->close();
            }
            catch (\Throwable $_)
            {
                // Ignore
            }
        }
        if ($this->resource)
        {
            \fclose($this->resource);
        }
    }

}
