<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsString;

abstract class VO_Decimal extends VO_String_Trimmed implements ICanGetValueAsFloat {

  /**
   * @var string[] array containing $value parts [Decimal, Fraction?]. i.e. ["100","05"] or ["500"]
   */
  private array $parts;
  private float $loatRepresentationOfValue;

  /**
   * @param string|ICanGetValueAsString $value i.e. 100.05 or 500 etc.
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $value) {
    parent::__construct($value);
    $value = $this->getValueAsString();
    $this->loatRepresentationOfValue = filter_var($value, FILTER_VALIDATE_FLOAT);
    if ($this->loatRepresentationOfValue === false) {
      throw new InvalidArgumentException('$value is not valid decimal value.',['$value' => $value]);
    }
    $this->parts = explode('.',$value,2);
  }

  public function getValueAsFloat():float
  {
    return $this->loatRepresentationOfValue;
  }

  public function getValueAsString():string {
    return $this->getValue();
  }

}
