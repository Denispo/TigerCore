<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class InvalidCredentialsException extends BaseResponseException {
  public function __construct(ICanGetPayload $payload, string $message = '') {
    parent::__construct($payload, $message, IResponse::S401_UNAUTHORIZED);
  }
}