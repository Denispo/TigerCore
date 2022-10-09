<?php

namespace TigerCore\Request\Validator;

class ParamErrorCode_TooHigh extends BaseParamErrorCode {


  public function getErrorCodeValue(): int|string
  {
    return 'TOO_HIGH';
  }
}
