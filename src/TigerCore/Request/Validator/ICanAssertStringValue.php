<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

interface ICanAssertStringValue {

  public function runAssertion(ICanGetValueAsString $requestParam):BaseParamErrorCode|null;

}
