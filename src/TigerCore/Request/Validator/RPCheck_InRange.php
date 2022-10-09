<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_InRange extends BaseRequestParamValidator implements ICanValidateIntRequestParam {

  public function __construct(private int|float $min, private int|float $max ) {
  }

  public function isRequestParamValid(ICanGetValueAsInit $requestParam): bool
  {
    $value = $requestParam->getValueAsInt();
    return $value >= $this->min && $value <= $this->max;
  }

  public function getCustomErrorCode(): BaseParamErrorCode
  {
    // TODO: Implement getCustomErrorCode() method.
  }
}
