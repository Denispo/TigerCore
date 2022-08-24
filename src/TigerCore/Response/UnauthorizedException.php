<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnauthorizedException extends BaseResponseException {
  public function __construct(ICanGetPayloadData|string $payload = '') {
    parent::__construct(IResponse::S401_UNAUTHORIZED, $payload);
  }

}