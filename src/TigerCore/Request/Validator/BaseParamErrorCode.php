<?php

namespace TigerCore\Request\Validator;

use TigerCore\ValueObject\VO_RequestParamErrorCode;

abstract class BaseParamErrorCode  {

  public abstract function getErrorCodeValue(): VO_RequestParamErrorCode;

}
