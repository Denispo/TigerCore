<?php

namespace TigerCore\Request\Validator;

use TigerCore\ValueObject\VO_RequestParamName;

final class InvalidRequestParam  {

  public function __construct(private VO_RequestParamName $paramName, private BaseParamErrorCode $errorCode){
  }

  /**
   * @return VO_RequestParamName
   */
  public function getParamName(): VO_RequestParamName
  {
    return $this->paramName;
  }

  /**
   * @return BaseParamErrorCode
   */
  public function getErrorCode(): BaseParamErrorCode
  {
    return $this->errorCode;
  }

}
