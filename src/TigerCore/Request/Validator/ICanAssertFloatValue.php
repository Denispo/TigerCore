<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsFloat;

interface ICanAssertFloatValue {

  public function runAssertion(ICanGetValueAsFloat|float $requestParam):BaseParamErrorCode|null;

}
