<?php

namespace TigerCore\Request\Validator;

class ParamErrorCode_Empty extends BaseParamErrorCode {


  public function getErrorCodeValue(): int|string
  {
    return 'EMPTY';
  }
}
