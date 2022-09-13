<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnprocessableEntityException extends BaseResponseException {
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct(IResponse::S422_UNPROCESSABLE_ENTITY, $message, $customData);
  }
}