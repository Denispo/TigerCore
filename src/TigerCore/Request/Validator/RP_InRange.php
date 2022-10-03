<?php

namespace TigerCore\Request\Validator;

use TigerCore\Requests\BaseRequestParam;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RP_InRange extends BaseRPValidator {

  public function __construct(private int|float $min, private int|float $max) {
  }

  protected function runCheck(BaseRequestParam $requestParamValue)
  {
    // TODO: Implement runCheck() method.
  }
}
