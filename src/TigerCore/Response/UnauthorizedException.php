<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnauthorizedException extends BaseResponseException {
  public function __construct(ICanGetPayload|string $payload = '') {
    parent::__construct(IResponse::S401_UNAUTHORIZED, $payload);
  }

}