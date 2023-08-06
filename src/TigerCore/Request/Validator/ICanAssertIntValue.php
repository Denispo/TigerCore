<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

interface ICanAssertIntValue {

  public function runAssertion(ICanGetValueAsInit $requestParam):BaseParamErrorCode|null;

}
