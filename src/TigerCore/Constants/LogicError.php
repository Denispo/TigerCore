<?php

namespace TigerCore\Constants;


class LogicError extends BaseConst implements IBaseConst {

  const int LERR_NA = 0;
  const int LERR_NOT_FOUND = 1;
  const int LERR_ALREADY_EXISTS = 2;
  const int LERR_ALREADY_DONE = 3;


  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
