<?php

namespace TigerCore\Constants;


class TokenError extends BaseConst implements IBaseConst {

  const int ERR_NA = 0;
  const int ERR_INVALID_ARGUMENT = 1;
  const int ERR_INVALID_DOMAIN = 2;
  const int ERR_UNEXPECTED_VALUE = 3;
  const int ERR_INVALID_SIGNATURE = 4;
  const int ERR_BEFORE_VALID = 5;
  const int ERR_EXPIRED = 6;
  const int ERR_INVALID_AUTHENTICATION_TIME = 7;
  const int ERR_INVALID_KEYID = 8;

  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
