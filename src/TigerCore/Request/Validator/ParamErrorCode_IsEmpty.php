<?php

namespace TigerCore\Request\Validator;

class ParamErrorCode_IsEmpty extends BaseParamErrorCode {


  public function getErrorCodeValue(): int|string
  {
    return 'IS_EMPTY';
  }
}
