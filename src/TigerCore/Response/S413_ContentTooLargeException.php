<?php

namespace TigerCore\Response;


/**
 * The HTTP 413 Content Too Large response status code indicates that the request entity is larger than limits defined by server; the server might close the connection or return a Retry-After header field.
 *
 * Prior to RFC 9110 the response phrase for the status was Payload Too Large. That name is still widely used.
 */

#[ResponseCode(413)]
class S413_ContentTooLargeException extends Base_4xx_RequestException {

}