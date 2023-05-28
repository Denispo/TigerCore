<?php

namespace TigerCore\ValueObject;

use TigerCore\ICanGetValueAsBoolean;
use TigerCore\ICanGetValueAsInit;

abstract class VO_Boolean extends BaseValueObject implements ICanGetValueAsBoolean{

  /**
   * If int provided, boolval() is used to convert int to boolean
   * @param bool|int|ICanGetValueAsBoolean|ICanGetValueAsInit $value
   */
  public function __construct(bool|int|ICanGetValueAsBoolean|ICanGetValueAsInit $value) {
    if ($value instanceof ICanGetValueAsBoolean) {
      $value = $value->getValueAsBool();
    } elseif ($value instanceof ICanGetValueAsInit){
      $value = $value->getValueAsInt();
    }

    if (is_int($value)){
      $value = boolval($value);
    }

    parent::__construct($value);
  }

  public function getValueAsBool():bool {
    return $this->getValue();
  }

}
