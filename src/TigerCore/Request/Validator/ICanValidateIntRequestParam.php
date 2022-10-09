<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

interface ICanValidateIntRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsInit $requestParam):BaseParamErrorCode|null;

}
