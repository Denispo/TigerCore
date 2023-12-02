<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_Url extends VO_String_Trimmed {
  /**
   * @param ICanGetValueAsString|string $url
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $url)
  {
    parent::__construct($url);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Url can not be empty string');
    }
  }

}
