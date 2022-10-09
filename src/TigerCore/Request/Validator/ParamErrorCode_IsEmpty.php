<?php

namespace TigerCore\Request\Validator;

use TigerCore\ValueObject\VO_RequestParamErrorCode;

class ParamErrorCode_IsEmpty extends BaseParamErrorCode {


  public function getErrorCodeValue(): VO_RequestParamErrorCode
  {
    return new VO_RequestParamErrorCode('EMPTY');
  }
}
