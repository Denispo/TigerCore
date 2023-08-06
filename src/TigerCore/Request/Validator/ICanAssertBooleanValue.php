<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsBoolean;

interface ICanAssertBooleanValue {

  public function runAssertion(ICanGetValueAsBoolean $requestParam):BaseParamErrorCode|null;

}
