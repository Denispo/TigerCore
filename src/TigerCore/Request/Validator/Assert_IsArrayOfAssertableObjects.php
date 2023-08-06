<?php

namespace TigerCore\Request\Validator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsArrayOfAssertableObjects extends BaseRequestParamValidator implements ICanAssertArrayOfAssertableObjects {

  /**
   * @param class-string $className
   */
  public function __construct(string $className)
  {
  }

  public function runAssertion(array $requestParam): BaseParamErrorCode|null
  {
    return null;
  }
}
