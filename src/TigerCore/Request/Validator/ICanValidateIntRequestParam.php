<?php

namespace TigerCore\Request\Validator;

use TigerCore\Requests\RP_Int;

interface ICanValidateIntRequestParam {

  public function isIntRequestParamValid(RP_Int $requestParam):bool;

}
