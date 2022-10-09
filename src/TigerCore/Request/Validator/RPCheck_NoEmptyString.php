<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_NoEmptyString extends BaseRequestParamValidator implements ICanValidateStrRequestParam {

  public function __construct() {
  }

  public function isRequestParamValid(ICanGetValueAsString $requestParam): bool
  {
    return $requestParam->getValueAsString() !== '';
  }
}
