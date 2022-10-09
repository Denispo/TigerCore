<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RPCheck_IsInRange extends BaseRequestParamValidator implements ICanValidateIntRequestParam {

  public function __construct(private int|float $min, private int|float $max ) {
  }

  public function checkRequestParamValidity(ICanGetValueAsInit $requestParam): BaseParamErrorCode|null
  {
    $value = $requestParam->getValueAsInt();
    $errorCode = null;
    if ($value < $this->min) {
      $errorCode = new ParamErrorCode_TooLow();
    }
    if ($value > $this->max) {
      $errorCode = new ParamErrorCode_TooHigh();
    }
    return $errorCode;
  }

}
