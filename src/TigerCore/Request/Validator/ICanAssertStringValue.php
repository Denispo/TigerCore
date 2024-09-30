<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

interface ICanAssertStringValue {

  public function runAssertion(ICanGetValueAsString|string $requestParam):BaseParamErrorCode|null;

}
