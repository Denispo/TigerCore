<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;
use TigerCore\Payload\ICanGetPayloadRawData;

class UnauthorizedException extends BaseResponseException {
  public function __construct(ICanGetPayloadRawData|string $payload = '') {
    parent::__construct(IResponse::S401_UNAUTHORIZED, $payload);
  }

}