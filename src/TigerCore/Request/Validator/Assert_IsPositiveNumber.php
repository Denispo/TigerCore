<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsPositiveNumber extends BaseAssertion implements ICanAssertIntValue {

  public function runAssertion(ICanGetValueAsInit|int $requestParam): BaseParamErrorCode|null
  {
     $value = parent::getValueAsInt($requestParam);
     return $value > 0 ? null : new ParamErrorCode_TooLow();
  }
}
