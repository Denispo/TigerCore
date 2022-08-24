<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnprocessableEntityException extends BaseResponseException {
  public function __construct(ICanGetPayloadData|string $payload = '') {
    parent::__construct(IResponse::S422_UNPROCESSABLE_ENTITY, $payload);
  }
}