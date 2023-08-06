<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AssertEmailAddress extends BaseRequestParamValidator implements ICanAssertStringValue {

  public function runAssertion(ICanGetValueAsString $requestParam): BaseParamErrorCode|null
  {
    return filter_var($requestParam->getValueAsString(), FILTER_VALIDATE_EMAIL) === false ? new ParamErrorCode_Invalid() : null;
  }
}
