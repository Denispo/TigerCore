<?php

namespace TigerCore\ValueObject;


use JetBrains\PhpStorm\NoReturn;

abstract class BaseValueObject {

    protected mixed $value;

    public function __construct(mixed $value) {
        $this->value = $value;
    }

    abstract function getValue():mixed;

    abstract function isEmpty():bool;

  /**
   * @return void
   * @throws \Exception
   */
  #[NoReturn]
  public function __toString() {
    throw new \Exception('getValue() must be called');
  }

}
