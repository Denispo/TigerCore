<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\ValueObject\BaseValueObject;

abstract class BaseMappedType extends BaseValueObject implements ICanGetValueAsString, ICanGetValueAsInit {

  private array $map = [];

  /**
   *
   * Status map is [int => string, int => string]
   * "int" is const value, "string" is const name
   * @return array
   */
  abstract protected function onGetMap():array;

  /**
   * @param int|string|ICanGetValueAsInit|ICanGetValueAsString $value
   * @throws InvalidArgumentException
   */
  public function __construct(int|string|ICanGetValueAsInit|ICanGetValueAsString $value) {
    if ($value instanceof ICanGetValueAsInit) {
      $value = $value->getValueAsInt();
    } elseif ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    $this->map = $this->onGetMap();
    if (is_string($value)) {
      $value = strtoupper($value);

      $value = array_search($value, $this->map);
      if ($value === false) {
        throw new InvalidArgumentException();
      }
    } else {
      if (!array_key_exists($value, $this->map)) {
        throw new InvalidArgumentException();
      }
    }
    parent::__construct($value);
  }

  public function getValueAsInt(): int
  {
    return $this->getValue();
  }

  public function getValueAsString(): string
  {
    return $this->map[$this->getValueAsInt()];
  }

}
