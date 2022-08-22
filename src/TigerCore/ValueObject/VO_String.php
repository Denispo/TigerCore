<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsString;

abstract class VO_String extends BaseValueObject {

  public function __construct(string|ICanGetValueAsString $value, bool $trim = false) {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    if ($trim) {
      $value = trim($trim);
    }
    parent::__construct($value);
  }

  public function getValue():string {
    return $this->value;
  }
}
