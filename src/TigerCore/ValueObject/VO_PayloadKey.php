<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_PayloadKey extends VO_String_Trimmed {
  /**
   * @param ICanGetValueAsString|string $value
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $value)
  {
    parent::__construct($value);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Payload key name can not be empty string');
    }

    /**
     * \A     Start of string
     * \z     End of string
     */
    if (!preg_match('/\A([a-zA-Z0-9_]+)\z/', $this->getValueAsString())) {
      throw new InvalidArgumentException('Payload key name has to contains only this characters: a-z A-Z 0-9 _');
    }

  }
}
