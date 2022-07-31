<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class MethodNotAllowedException extends BaseResponseException {
  public function __construct(string $message = '') {
    parent::__construct($message, IResponse::S405_METHOD_NOT_ALLOWED);
  }

}