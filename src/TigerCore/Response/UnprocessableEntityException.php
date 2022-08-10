<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnprocessableEntityException extends BaseResponseException {
  public function __construct(ICanGetPayload $payload, string $message = '') {
    parent::__construct($payload, $message, IResponse::S422_UNPROCESSABLE_ENTITY);
  }
}