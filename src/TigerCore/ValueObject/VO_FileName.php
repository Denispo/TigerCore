<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_FileName extends VO_String_Trimmed {
  /**
   * @param ICanGetValueAsString|string $fileName
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $fileName)
  {
    parent::__construct($fileName);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('file name can not be empty string');
    }
  }

}
