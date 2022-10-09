<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsBoolean;

interface ICanValidateBooleanRequestParam {

  public function isRequestParamValid(ICanGetValueAsBoolean $requestParam):bool;

}
