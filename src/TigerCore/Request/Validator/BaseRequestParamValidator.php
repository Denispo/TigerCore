<?php

namespace TigerCore\Request\Validator;

abstract class BaseRequestParamValidator{

  public abstract function getCustomErrorCode():BaseParamErrorCode;

}
