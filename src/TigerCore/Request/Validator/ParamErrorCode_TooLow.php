<?php

namespace TigerCore\Request\Validator;

class ParamErrorCode_TooLow extends BaseParamErrorCode {

  public function getErrorCodeValue(): int|string
  {
    return 'TOO_LOW';
  }
}
