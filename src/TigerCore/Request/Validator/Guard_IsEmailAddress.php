<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Guard_IsEmailAddress extends BaseRequestParamValidator implements ICanGuardStrRequestParam {

  public function runGuard(ICanGetValueAsString $requestParam): BaseParamErrorCode|null
  {
    return filter_var($requestParam->getValueAsString(), FILTER_VALIDATE_EMAIL) === false ? new ParamErrorCode_Invalid() : null;
  }
}
