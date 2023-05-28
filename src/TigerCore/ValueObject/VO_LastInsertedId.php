<?php

namespace TigerCore\ValueObject;

use TigerCore\ICanGetValueAsMixed;

class VO_LastInsertedId extends BaseValueObject implements ICanGetValueAsMixed {

  public function getValueAsMixed(): mixed
  {
    return $this->getValue();
  }
}
