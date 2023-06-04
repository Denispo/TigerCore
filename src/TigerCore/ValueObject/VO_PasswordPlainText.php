<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_PasswordPlainText extends VO_String {
  /**
   * @param ICanGetValueAsString|string $value
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $value)
  {
    parent::__construct($value, false);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Password can not be empty string');
    }

  }
}
