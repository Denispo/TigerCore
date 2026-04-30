<?php

namespace TigerCore\Constants;


class Environment extends BaseConst implements IBaseConst {

  const int ENV_PRODUCTION = 1;
  const int ENV_DEVELOPMENT = 2;


  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
