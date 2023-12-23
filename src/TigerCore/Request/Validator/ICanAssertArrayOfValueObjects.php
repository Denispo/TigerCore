<?php

namespace TigerCore\Request\Validator;

interface ICanAssertArrayOfValueObjects {

  /**
   * @return class-string BaseValueObject class name
   */
  public function getValueObjectClassName(): string;

}
