<?php

namespace TigerCore\Request\Validator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsArrayOfAssertableObjects extends BaseAssertionArray implements ICanAssertArrayOfAssertableObjects {

  /**
   * @param class-string $assertableObjectClassName
   */
  public function __construct(private string $assertableObjectClassName)
  {
  }

  public function getAssertableObjectName(): string
  {
    return $this->assertableObjectClassName;
  }
}
