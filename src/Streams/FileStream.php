<?php

namespace Fastwf\Api\Streams;

use Fastwf\Api\Utils\ArrayUtil;
use Psr\Http\Message\StreamInterface;
use Fastwf\Api\Exceptions\IOException;
use Fastwf\Api\Http\Frame\HttpRequestInterface;

/**
 * A StreamInterface implementation for files.
 */
class FileStream implements StreamInterface
{

    /**
     * The list of readable file mode.
     */
    private const READABLE_MODES = ['r', 'a+', 'ab+', 'w+', 'wb+', 'x+', 'xb+', 'c+', 'cb+'];
    
    /**
     * The list of writeable file mode.
     */
    private const WRITABLE_MODES = ['a', 'w', 'r+', 'rb+', 'rw', 'x', 'c'];

    /**
     * The path to the file.
     *
     * @var string
     */
    protected $path;

    /**
     * The open mode of the file.
     *
     * @var string
     */
    protected $mode;

    /**
     * True when the stream is readable.
     *
     * @var boolean
     */
    protected $readable;

    /**
     * True when the stream is writeable.
     *
     * @var boolean
     */
    protected $writeable;

    /**
     * The internal stream resource.
     *
     * @var resource
     */
    protected $stream;

    /**
     * The file size in bytes.
     *
     * @var int|null
     */
    protected $size;

    /**
     * The file metadata.
     *
     * @var array|null
     */
    protected $metaData;

    public function __construct($path, $mode, $size = null)
    {
        $this->path = $path;
        $this->mode = $mode;

        $this->size = $size;

        $this->readable = in_array($mode, self::READABLE_MODES);
        $this->writeable = in_array($mode, self::WRITABLE_MODES);
    }

    /**
     * Identify if the stream is open or not (when not opened an exception is thrown).
     *
     * @throws IOException an exception if the stream is not opened and $throw is true.
     */
    protected function whenOpen()
    {
        if ($this->stream === null)
        {
            throw new IOException("The stream is not opened");
        }
    }

    public function __toString()
    {
        if ($this->readable)
        {
            $this->rewind();
    
            return $this->read($this->getSize()); 
        }
        else
        {
            return "";
        }
    }

    public function close()
    {
        $this->whenOpen();

        fclose($this->stream);
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        $detachedStream = $this->stream;
        $this->stream = null;

        return $detachedStream;
    }

    public function getSize()
    {
        // When it's not writable return the size set in constructor.
        //  else call stat to obtain file size informations
        if (!$this->isWritable() && $this->size !== null)
        {
            return $this->size;
        }
        else if ($this->stream !== null)
        {
            $stat = fstat($this->stream);
        }
        else
        {
            $stat = stat($this->path);
        }

        return $stat !== false ? $stat['size'] : null;
    }

    public function tell()
    {
        $this->whenOpen();

        return ftell($this->stream);
    }

    public function eof()
    {
        $this->whenOpen();

        return feof($this->stream);
    }

    public function isSeekable()
    {
        $this->whenOpen();

        return (boolean) $this->getMetadata('seekable');
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->whenOpen();

        fseek($this->stream, $offset, $whence);
    }

    public function rewind()
    {
        // Open the stream or return to the beggining of the stream
        if ($this->stream === null)
        {
            $this->stream = fopen($this->path, $this->mode);
        }
        else
        {
            rewind($this->stream);
        }
    }

    public function isWritable()
    {
        return $this->stream !== null && $this->writeable;
    }

    public function write($string)
    {
        // Open the stream before write call
        if ($this->stream === null)
        {
            $this->stream = fopen($this->path, $this->mode);
        }

        return fwrite($this->stream, $string);
    }

    public function isReadable()
    {
        return $this->stream !== null && $this->readable;
    }

    public function read($length)
    {
        $this->whenOpen();

        return fread($this->stream, $length);
    }

    public function getContents()
    {
        $buffer = "";

        while (!$this->eof())
        {
            $buffer .= $this->read(HttpRequestInterface::BUFFER);
        }

        return $buffer;
    }

    public function getMetadata($key = null)
    {
        $this->whenOpen();

        // Load metadata
        if ($this->metaData === null)
        {
            $this->metaData = stream_get_meta_data($this->stream);
        }

        // Return all metadata or just the metadata corresponding to the key (null if not found)
        if ($key === null)
        {
            return $this->metaData;
        }
        else
        {
            return ArrayUtil::getSafe($this->metaData, $key);
        }
    }

}
