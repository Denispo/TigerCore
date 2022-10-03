<?php

namespace TigerCore\Request\Validator;

use TigerCore\Requests\BaseRequestParam;

abstract class BaseRPValidator {

  abstract protected function runCheck(BaseRequestParam $requestParamValue);

}
