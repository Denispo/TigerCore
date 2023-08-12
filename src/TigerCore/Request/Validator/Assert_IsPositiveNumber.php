<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsPositiveNumber extends BaseAssertion implements ICanAssertIntValue {

  public function runAssertion(ICanGetValueAsInit $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsInt() > 0 ? null : new ParamErrorCode_TooLow();
  }
}
