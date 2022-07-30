<?php

namespace TigerCore\Constants;


class RequestMatchResult extends BaseConst implements IBaseConst {

  const RESULT_OK = 1;
  const RESULT_TRY_MATCH_ANOTHER_ROUTE = 2;

  public function IsSetTo($value): bool {
    return parent::IsSetToValue($value);
  }
}
