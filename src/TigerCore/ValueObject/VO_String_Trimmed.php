<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsString;

abstract class VO_String_Trimmed extends BaseValueObject {

  public function __construct(int|ICanGetValueAsString $value) {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    parent::__construct($value, true);
  }

  public function getValue():string {
    return $this->value;
  }
}
