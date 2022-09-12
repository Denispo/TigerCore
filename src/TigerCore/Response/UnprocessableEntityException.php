<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;
use TigerCore\Payload\ICanGetPayloadRawData;

class UnprocessableEntityException extends BaseResponseException {
  public function __construct(ICanGetPayloadRawData|string $payload = '') {
    parent::__construct(IResponse::S422_UNPROCESSABLE_ENTITY, $payload);
  }
}