<?php

namespace TigerCore\ValueObject;

use TigerCore\ICanGetValueAsBoolean;

abstract class VO_Boolean extends BaseValueObject {

  public function __construct(int|ICanGetValueAsBoolean $value) {
    if ($value instanceof ICanGetValueAsBoolean) {
      $value = $value->getValueAsBool();
    }
    parent::__construct($value);
  }

  public function getValue():bool {
    return $this->value;
  }

}
