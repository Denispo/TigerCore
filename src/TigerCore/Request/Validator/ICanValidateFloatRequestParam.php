<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsFloat;

interface ICanValidateFloatRequestParam {

  public function isRequestParamValid(ICanGetValueAsFloat $requestParam):bool;

}
