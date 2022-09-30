<?php

namespace TigerCore\Response;


/**
 * The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, size too large, invalid request message framing, or deceptive request routing).
 */
#[ResponseCode(400)]
class S400_BadRequestException extends Base_4xx_RequestException {


}