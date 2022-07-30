<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class InvalidCredentialsException extends BaseResponseException {
  public function __construct(string $message = '') {
    parent::__construct($message, IResponse::S401_UNAUTHORIZED);
  }
}