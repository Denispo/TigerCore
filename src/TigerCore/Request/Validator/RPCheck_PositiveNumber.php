<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_PositiveNumber extends BaseRequestParamValidator implements ICanValidateIntRequestParam {

  public function __construct() {
  }

  public function isRequestParamValid(ICanGetValueAsInit $requestParam): bool
  {
    return $requestParam->getValueAsInt() > 0;
  }
}
