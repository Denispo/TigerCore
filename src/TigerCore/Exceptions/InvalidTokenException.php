<?php

namespace TigerCore\Exceptions;



use TigerCore\Constants\TokenError;

class InvalidTokenException extends _BaseException
{
  public function __construct(private TokenError $tokenError ,string $messahe = '', int $code = 0, null|\Throwable $previous = null) {
    parent::__construct($messahe, $code, $previous);
  }

  public function getTokenError():TokenError {
    return $this->tokenError;
  }
}
