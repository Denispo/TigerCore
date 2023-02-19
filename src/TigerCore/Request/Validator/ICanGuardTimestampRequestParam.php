<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

interface ICanGuardTimestampRequestParam {

  public function runGuard(ICanGetValueAsTimestamp $requestParam):BaseParamErrorCode|null;

}
