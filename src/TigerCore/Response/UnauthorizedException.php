<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class UnauthorizedException extends BaseResponseException {
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct(IResponse::S401_UNAUTHORIZED, $message, $customData);
  }

}