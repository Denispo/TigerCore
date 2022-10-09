<?php

namespace TigerCore\Request\Validator;

use TigerCore\ValueObject\VO_RequestParamErrorCode;

class ParamErrorCode_TooLow extends BaseParamErrorCode {

  public function getErrorCodeValue(): VO_RequestParamErrorCode
  {
    return new VO_RequestParamErrorCode('TOO_LOW');
  }
}
