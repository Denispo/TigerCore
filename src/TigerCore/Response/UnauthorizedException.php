<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnauthorizedException extends BaseResponseException {
  public function __construct(ICanGetPayload|null $payload = null, string $message = '') {
    parent::__construct($payload, $message, IResponse::S401_UNAUTHORIZED);
  }

}