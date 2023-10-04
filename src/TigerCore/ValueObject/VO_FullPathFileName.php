<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_FullPathFileName extends VO_String_Trimmed {
  /**
   * @param ICanGetValueAsString|string $fullPathFileName
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $fullPathFileName)
  {
    parent::__construct($fullPathFileName);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('file name can not be empty string');
    }
  }

}
