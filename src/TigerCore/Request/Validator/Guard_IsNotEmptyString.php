<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Guard_IsNotEmptyString extends BaseRequestParamValidator implements ICanGuardStrRequestParam {

  public function runGuard(ICanGetValueAsString $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsString() === '' ? new ParamErrorCode_IsEmpty() : null;
  }
}
