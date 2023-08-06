<?php

namespace TigerCore\Request\Validator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Guard_IsArrayOfObjects extends BaseRequestParamValidator implements ICanGuardArrayOfObjectsRequestparam {

  /**
   * @param class-string $className
   */
  public function __construct(string $className)
  {
  }

  public function runGuard(array $requestParam): BaseParamErrorCode|null
  {
    return null;
  }
}
