<?php

namespace TigerCore\Request\Validator;

use TigerCore\Requests\BaseRequestParam;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_Required extends BaseRequestParamValidator implements ICanValidateBaseRequestParam {


  public function isRequestBaseParamValid(BaseRequestParam $requestParam): bool
  {
    return $requestParam->isSet();
  }
}
