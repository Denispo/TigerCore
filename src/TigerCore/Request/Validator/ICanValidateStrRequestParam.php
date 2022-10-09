<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

interface ICanValidateStrRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsString $requestParam):BaseParamErrorCode|null;

}
