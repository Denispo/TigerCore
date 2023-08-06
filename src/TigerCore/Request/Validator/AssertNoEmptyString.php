<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AssertNoEmptyString extends BaseRequestParamValidator implements ICanAssertStringValue {

  public function runAssertion(ICanGetValueAsString $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsString() === '' ? new ParamErrorCode_IsEmpty() : null;
  }
}
