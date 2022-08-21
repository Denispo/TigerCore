<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsInit;

abstract class VO_Int extends BaseValueObject {

  public function __construct(int|ICanGetValueAsInit $value) {
    if ($value instanceof ICanGetValueAsInit) {
      $value = $value->getValueAsInt();
    }
    parent::__construct($value);
  }

  public function getValue():int {
    return $this->value;
  }

}
