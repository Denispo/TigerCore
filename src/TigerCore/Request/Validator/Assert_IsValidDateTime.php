<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsValidDateTime extends BaseRequestParamValidator implements ICanAssertTimestampValue {

  public function runAssertion(ICanGetValueAsTimestamp $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsTimestamp()->isValid() ? null : new ParamErrorCode_TooLow();
  }
}
