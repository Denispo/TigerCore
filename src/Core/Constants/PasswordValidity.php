<?php

namespace Core\Constants;


class PasswordValidity extends BaseConst implements IBaseConst {

  const PWD_INVALID = 0;
  const PWD_VALID = 1;

  public static function createFromBoolean(bool $isPasswordValid):self {
    if ($isPasswordValid) {
      return new self(self::PWD_VALID);
    } else {
      return new self(self::PWD_INVALID);
    }
  }

  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
