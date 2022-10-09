<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsBoolean;

interface ICanValidateBooleanRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsBoolean $requestParam):BaseParamErrorCode|null;

}
