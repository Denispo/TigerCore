<?php

namespace TigerCore\Request\Validator;

class ParamErrorCode_InvalidEmailAddress extends BaseParamErrorCode {


  public function getErrorCodeValue(): int|string
  {
    return 'INVALID_EMAIL_ADDRESS';
  }
}
