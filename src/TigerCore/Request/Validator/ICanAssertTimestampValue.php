<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

interface ICanAssertTimestampValue {

  public function runAssertion(ICanGetValueAsTimestamp $requestParam):BaseParamErrorCode|null;

}
