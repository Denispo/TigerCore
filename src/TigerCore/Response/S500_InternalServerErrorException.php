<?php

namespace TigerCore\Response;


/**
 * A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
 */
#[ResponseCode(500)]
class S500_InternalServerErrorException extends Base_5xx_RequestException {
}