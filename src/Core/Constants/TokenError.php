<?php

namespace Core\Constants;


class TokenError extends BaseConst implements IBaseConst {

  const ERR_NA = 0;
  const ERR_INVALID_ARGUMENT = 1;
  const ERR_INVALID_DOMAIN = 2;
  const ERR_UNEXPECTED_VALUE = 3;
  const ERR_INVALID_SIGNATURE = 4;
  const ERR_BEFORE_VALID = 5;
  const ERR_EXPIRED = 6;

  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
