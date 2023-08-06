<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsFloat;

interface ICanAssertFloatValue {

  public function runAssertion(ICanGetValueAsFloat $requestParam):BaseParamErrorCode|null;

}
