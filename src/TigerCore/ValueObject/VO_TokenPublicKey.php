<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_TokenPublicKey extends VO_String_Trimmed {
  /**
   * @param ICanGetValueAsString|string $publicKey
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $publicKey)
  {
    parent::__construct($publicKey);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Token public key can not be empty string');
    }
  }
}
