<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Guard_IsValidDateTime extends BaseRequestParamValidator implements ICanGuardTimestampRequestParam {

  public function runGuard(ICanGetValueAsTimestamp $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsTimestamp()->isValid() ? null : new ParamErrorCode_TooLow();
  }
}
