<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsFloat;

interface ICanValidateFloatRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsFloat $requestParam):BaseParamErrorCode|null;

}
