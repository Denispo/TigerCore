<?php

namespace TigerCore\Response;


/**
 * The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.
 */
#[ResponseCode(406)]
class S406_NotAcceptableException extends Base_4xx_RequestException {

}