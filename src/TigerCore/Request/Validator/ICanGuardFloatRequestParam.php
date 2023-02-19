<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsFloat;

interface ICanGuardFloatRequestParam {

  public function runGuard(ICanGetValueAsFloat $requestParam):BaseParamErrorCode|null;

}
