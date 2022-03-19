<?php

namespace Fastwf\Api\Http\Frame;

use Fastwf\Api\Utils\ArrayProxy;

/**
 * The object representation of the http request.
 * 
 * @property-read string $path The path corresponding to the REQUEST_URI.
 * @property-read string $method The request method corresponding to the REQUEST_METHOD.
 * @property-read ArrayProxy $query the array of query parameters ($_GET)
 * @property-read ArrayProxy $form the array that contains the parsed form data ($_POST).
 * @property-read string $body the sequence read from body request.
 * @property-read array $json the json as array association extracted from body request.
 * @property-read resource $stream the stream of the body request.
 * @property-read ArrayProxy $headers the request headers.
 * @property-read ArrayProxy $cookie the request cookies.
 */
interface HttpRequestInterface {

    public const BUFFER = 2**16;

    public const QUERY = 'query';
    public const FORM = 'form';
    public const STREAM = 'stream';
    public const BODY = 'body';
    public const JSON = 'json';
    public const FILES = 'files';
    public const PATH = 'path';
    public const METHOD = 'method';
    public const HEADERS = 'headers';
    public const COOKIE = 'cookie';

}
