<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_PayloadKey extends VO_String_Trimmed {
  /** Key name can contain only "a-z", "A-Z", "0-9" or "_" characters. Can not be empty string and can not start with number character
   * @param ICanGetValueAsString|string $keyName
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $keyName)
  {
    parent::__construct($keyName);
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

    // Checks if all of the characters in the provided string, text, are numerical.
    if (ctype_digit($this->getValueAsString()[0])) {
      throw new InvalidArgumentException('Payload key name can not start with numeric character');
    }

  }
}
