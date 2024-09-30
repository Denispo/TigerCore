<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsBoolean;

interface ICanAssertBooleanValue {

  public function runAssertion(ICanGetValueAsBoolean|bool $requestParam):BaseParamErrorCode|null;

}
