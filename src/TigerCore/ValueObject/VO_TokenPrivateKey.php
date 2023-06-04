<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_TokenPrivateKey extends VO_String_Trimmed {

  /**
   * @param ICanGetValueAsString|string $privateKey
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $privateKey)
  {
    parent::__construct($privateKey);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Token private key can not be empty string');
    }
  }

}
