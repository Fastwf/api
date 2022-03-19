<?php

namespace Fastwf\Api\Http\Frame;

use Fastwf\Api\Utils\ArrayProxy;
use Fastwf\Api\Engine\Output\HttpOutputInterface;


/**
 * Http Response that support streaming.
 * 
 * A response class that allows to develop it's stream logic.
 * This class is usefull when it's necessary to handle chunck from an input stream.
 * 
 * @property-read int $status the response status.
 * @property-read ArrayProxy $headers the http response headers to send to the client.
 */
interface HttpResponseInterface {

    /**
     * Allows to send the response to the client.
     *
     * @param HttpOutputInterface $httpOutput the resource where write the response.
     */
    public function send($httpOutput);

}
