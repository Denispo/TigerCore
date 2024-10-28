<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

interface ICanAssertIntValue {

  public function runAssertion(ICanGetValueAsInit|int $requestParam):BaseParamErrorCode|null;

}
