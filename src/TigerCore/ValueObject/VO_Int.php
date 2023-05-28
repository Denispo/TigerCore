<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsInit;

abstract class VO_Int extends BaseValueObject implements ICanGetValueAsInit{

  public function __construct(int|ICanGetValueAsInit $value) {
    if ($value instanceof ICanGetValueAsInit) {
      $value = $value->getValueAsInt();
    }
    parent::__construct($value);
  }

  public function getValueAsInt():int {
    return $this->getValue();
  }

}
