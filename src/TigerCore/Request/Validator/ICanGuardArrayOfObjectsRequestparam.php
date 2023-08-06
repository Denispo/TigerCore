<?php

namespace TigerCore\Request\Validator;

use TigerCore\Validator\BaseAssertableObject;

interface ICanGuardArrayOfObjectsRequestparam {

  /**
   * @param BaseAssertableObject[] $requestParam
   * @return BaseParamErrorCode|null
   */
  public function runGuard(array $requestParam):BaseParamErrorCode|null;

}
