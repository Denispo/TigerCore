<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnauthorizedException extends BaseResponseException {
  public function __construct(string $message = '') {
    parent::__construct($message, IResponse::S401_UNAUTHORIZED);
  }

}