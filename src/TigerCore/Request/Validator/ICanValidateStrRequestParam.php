<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

interface ICanValidateStrRequestParam {

  public function isRequestParamValid(ICanGetValueAsString $requestParam):bool;

}
