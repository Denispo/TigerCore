<?php

namespace TigerCore\Request\Validator;

use TigerCore\Requests\RP_Float;

interface ICanValidateFloatRequestParam {

  public function isFloatRequestParamValid(RP_Float $requestParam):bool;

}
