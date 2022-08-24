<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class MethodNotAllowedException extends BaseResponseException {
  public function __construct(ICanGetPayloadData|string $payload = '') {
    parent::__construct(IResponse::S405_METHOD_NOT_ALLOWED, $payload);
  }

}