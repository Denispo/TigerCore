<?php

namespace TigerCore\Request\Validator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsArrayOfValueObjects extends BaseAssertionArray implements ICanAssertArrayOfValueObjects {

  /**
   * @param class-string $valueObjectClassName
   */
  public function __construct(private string $valueObjectClassName)
  {
  }

  public function getValueObjectClassName(): string
  {
    return $this->valueObjectClassName;
  }
}
