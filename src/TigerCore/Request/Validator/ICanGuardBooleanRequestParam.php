<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsBoolean;

interface ICanGuardBooleanRequestParam {

  public function runGuard(ICanGetValueAsBoolean $requestParam):BaseParamErrorCode|null;

}
