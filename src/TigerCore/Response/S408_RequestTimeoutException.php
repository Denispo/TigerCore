<?php

namespace TigerCore\Response;


/**
 * The HyperText Transfer Protocol (HTTP) 408 Request Timeout response status code means that the server would like to shut down this unused connection. It is sent on an idle connection by some servers, even without any previous request by the client.
 *
 * A server should send the "close" Connection header field in the response, since 408 implies that the server has decided to close the connection rather than continue waiting.
 *
 * This response is used much more since some browsers, like Chrome, Firefox 27+, and IE9, use HTTP pre-connection mechanisms to speed up surfing.
 */

#[ResponseCode(408)]
class S408_RequestTimeoutException extends Base_4xx_RequestException {

}