<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnprocessableEntityException extends BaseResponseException {
  public function __construct(ICanGetPayload|string $payload = '') {
    parent::__construct(IResponse::S422_UNPROCESSABLE_ENTITY, $payload);
  }
}