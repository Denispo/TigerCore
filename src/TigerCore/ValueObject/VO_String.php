<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanCheckSelfEmptiness;
use TigerCore\ICanGetValueAsString;

abstract class VO_String extends BaseValueObject implements ICanGetValueAsString, ICanCheckSelfEmptiness {

  public function __construct(string|ICanGetValueAsString $value, bool $trim = false) {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    if ($trim) {
      $value = trim($value);
    }
    parent::__construct($value);
  }

  public function isEmpty(): bool {
    return $this->getValueAsString() === '';
  }

  public function getValueAsString():string {
    return $this->getValue();
  }
}
