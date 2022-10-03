<?php

namespace TigerCore\Request\Validator;

use TigerCore\Requests\RP_Int;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_InRange extends BaseRequestParamValidator implements ICanValidateIntRequestParam {

  public function __construct(private int|float $min, private int|float $max) {
  }

  public function isIntRequestParamValid(RP_Int $requestParam): bool
  {
    // TODO: Implement isIntRequestParamValid() method.
  }
}
