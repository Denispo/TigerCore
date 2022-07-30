<?php

namespace TigerCore\Constants;


class RequestMatchResult extends BaseConst implements IBaseConst {

  const RESULT_OK = 1;
  const RESULT_REQUEST_NOT_ALLOWED = 2;

  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
