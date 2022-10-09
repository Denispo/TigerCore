<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_IsNotEmptyString extends BaseRequestParamValidator implements ICanValidateStrRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsString $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsString() === '' ? new ParamErrorCode_IsEmpty() : null;
  }
}
