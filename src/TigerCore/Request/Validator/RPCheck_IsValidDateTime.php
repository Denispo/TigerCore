<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_IsValidDateTime extends BaseRequestParamValidator implements ICanValidateTimestampRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsTimestamp $requestParam): BaseParamErrorCode|null
  {
    return $requestParam->getValueAsTimestamp()->isValid() ? null : new ParamErrorCode_TooLow();
  }
}
