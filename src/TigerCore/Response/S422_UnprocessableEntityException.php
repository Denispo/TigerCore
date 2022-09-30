<?php

namespace TigerCore\Response;

/**
 * The request was well-formed but was unable to be followed due to semantic errors. The client should not repeat this request without modification.
 */
#[ResponseCode(422)]
class S422_UnprocessableEntityException extends Base_4xx_RequestException {
}