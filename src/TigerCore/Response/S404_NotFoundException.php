<?php

namespace TigerCore\Response;


/**
 * The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible.
 */
#[ResponseCode(404)]
class S404_NotFoundException extends Base_4xx_RequestException {


}