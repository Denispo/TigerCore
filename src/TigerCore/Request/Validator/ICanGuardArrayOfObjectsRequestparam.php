<?php

namespace TigerCore\Request\Validator;

use BaseJsonRequest;

interface ICanGuardArrayOfObjectsRequestparam {

  /**
   * @param BaseJsonRequest[] $requestParam
   * @return BaseParamErrorCode|null
   */
  public function runGuard(array $requestParam):BaseParamErrorCode|null;

}
