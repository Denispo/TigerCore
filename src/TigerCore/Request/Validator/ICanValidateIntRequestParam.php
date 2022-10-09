<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

interface ICanValidateIntRequestParam {

  public function isRequestParamValid(ICanGetValueAsInit $requestParam):bool;

}
