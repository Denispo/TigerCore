<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

interface ICanGuardStrRequestParam {

  public function runGuard(ICanGetValueAsString $requestParam):BaseParamErrorCode|null;

}
