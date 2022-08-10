<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class MethodNotAllowedException extends BaseResponseException {
  public function __construct(ICanGetPayload $payload, string $message = '') {
    parent::__construct($payload, $message, IResponse::S405_METHOD_NOT_ALLOWED);
  }

}