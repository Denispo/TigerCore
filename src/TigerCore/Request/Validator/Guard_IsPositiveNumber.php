<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Guard_IsPositiveNumber extends BaseRequestParamValidator implements ICanGuardIntRequestParam {

  public function runGuard(ICanGetValueAsInit $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsInt() > 0 ? null : new ParamErrorCode_TooLow();
  }
}
