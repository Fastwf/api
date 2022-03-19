<?php

namespace Fastwf\Api\Model;

use Psr\Http\Message\UploadedFileInterface;

/**
 * Wrapper for uploaded file sent by POST request.
 * 
 * This wrapper is a utility class that allows to acess to file information and help to handle them.
 */
class UploadedFile implements UploadedFileInterface
{

    /**
     * The name of the original uploaded file.
     *
     * @var string
     */
    private $clientFilename;

    /**
     * The mime type of the original uploaded file.
     *
     * @var string
     */
    private $clientMediaType;

    /**
     * The file size in bytes.
     *
     * @var int
     */
    private $size;

    /**
     * The path to the file.
     * 
     * The location is the temprary path created when body request is parsed. 
     *
     * @var string
     */
    private $path;

    /**
     * The error constant that represent the error when the file is uploaded.
     *
     * See https://www.php.net/manual/en/features.file-upload.errors.php
     * 
     * @var int
     */
    private $error;

    /**
     * Constructor of the class.
     *
     * @param array $file the file representation as array
     */
    public function __construct($file) {
        $this->clientFilename = $file["name"];
        $this->clientMediaType = $file["type"];
        $this->size = $file["size"];
        $this->path = $file["tmp_name"];
        $this->error = $file["error"];
    }

    /// IMPLEMENTATION

    public function getStream()
    {
        // TODO
        throw new \Exception("Not implemented");
    }

    public function moveTo($targetPath)
    {
        \move_uploaded_file($this->path, $targetPath);
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

    /// PUBLIC METHODS

    public function getPath()
    {
        return $this->path;
    }

}
