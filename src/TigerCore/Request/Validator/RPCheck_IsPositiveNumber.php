<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_IsPositiveNumber extends BaseRequestParamValidator implements ICanValidateIntRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsInit $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsInt() > 0 ? null : new ParamErrorCode_TooLow();
  }
}
