<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsString;

abstract class VO_Decimal extends VO_String_Trimmed implements ICanGetValueAsFloat {

  private int $fractionsCount = 0;

  /**
   * @param string|ICanGetValueAsString $value i.e. 100.50 or 500 etc.
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $value) {
    parent::__construct($value);
    $value = $this->getValueAsString();
    if (!(filter_var($value, FILTER_VALIDATE_FLOAT))) {
      throw new InvalidArgumentException('$value is not valid decimal value.',['$value' => $value]);
    }
  }

  public function getValueAsString():string {
    return $this->getValue();
  }

}
