<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_Hash extends VO_String_Trimmed {
  /**
   * @param ICanGetValueAsString|string $hash
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $hash)
  {
    parent::__construct($hash);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Hash can not be empty string');
    }
  }

}
