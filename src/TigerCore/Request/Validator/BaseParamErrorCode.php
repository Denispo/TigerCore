<?php

namespace TigerCore\Request\Validator;

abstract class BaseParamErrorCode  {

  public abstract function getErrorCodeValue(): int|string;

}
