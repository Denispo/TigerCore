<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_IsEmailAddress extends BaseRequestParamValidator implements ICanValidateStrRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsString $requestParam): BaseParamErrorCode|null
  {
    return filter_var($requestParam->getValueAsString(), FILTER_VALIDATE_EMAIL) === false ? new ParamErrorCode_Invalid() : null;
  }
}
