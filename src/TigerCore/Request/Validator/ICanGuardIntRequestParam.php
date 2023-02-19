<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

interface ICanGuardIntRequestParam {

  public function runGuard(ICanGetValueAsInit $requestParam):BaseParamErrorCode|null;

}
