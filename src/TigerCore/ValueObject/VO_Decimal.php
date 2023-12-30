<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;

abstract class VO_Decimal extends VO_String_Trimmed implements ICanGetValueAsFloat {

  /**
   * @var string[] array containing $value parts [Decimal, Fraction?]. i.e. ["100","05"] or ["500"]
   */
  private array $parts;
  private float $floatRepresentationOfValue;

  /**
   * @param int|string|ICanGetValueAsString|ICanGetValueAsInit $value i.e. 100.05 or 500 etc.
   * @throws InvalidArgumentException
   */
  public function __construct(int|string|ICanGetValueAsString|ICanGetValueAsInit $value) {
    if ($value instanceof ICanGetValueAsInit) {
      $value = $value->getValueAsInt();
    }
    parent::__construct((string)$value);
    $value = $this->getValueAsString();
    $this->floatRepresentationOfValue = filter_var($value, FILTER_VALIDATE_FLOAT);
    if ($this->floatRepresentationOfValue === false) {
      throw new InvalidArgumentException('$value is not valid decimal value.',['$value' => $value]);
    }
    $this->parts = explode('.',$value,2);
  }

  public function getValueAsFloat():float
  {
    return $this->floatRepresentationOfValue;
  }

  public function getValueAsString():string {
    return $this->getValue();
  }

}
