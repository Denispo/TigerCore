<?php

namespace TigerCore\ValueObject;


use JetBrains\PhpStorm\NoReturn;

abstract class BaseValueObject {

    private mixed $value;

    public function __construct(mixed $value) {
        $this->value = $value;
    }

    protected function getValue():mixed{
      return $this->value;
    }


  /**
   * @return void
   * @throws \Exception
   */
  #[NoReturn]
  public function __toString() {
    throw new \Exception('method getValueAsMixed() must be called. Value: "'.$this->value.'"');
  }

}
