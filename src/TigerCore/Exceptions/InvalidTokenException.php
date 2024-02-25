<?php

namespace TigerCore\Exceptions;



use TigerCore\Constants\TokenError;

class InvalidTokenException extends BaseTigerException
{
  public function __construct(private TokenError $tokenError , string $message = '', int $code = 0, null|\Throwable $previous = null) {
    parent::__construct($message,[], $previous, $code);
  }

  public function getTokenError():TokenError {
    return $this->tokenError;
  }
}
