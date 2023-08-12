<?php

namespace TigerCore\Request\Validator;

use TigerCore\ValueObject\VO_RequestParamName;

final class InvalidRequestParam  {

  /**
   * @param VO_RequestParamName $paramName Example: countryname
   * @param string $paramPath Path to paramName inside object in object etc. Example: country.india
   * @param BaseParamErrorCode|null $errorCode
   */
  public function __construct(
    private VO_RequestParamName $paramName,
    private string $paramPath = '',
    private BaseParamErrorCode|null $errorCode = null){
  }

  /**
   * @return VO_RequestParamName
   */
  public function getParamName(): VO_RequestParamName
  {
    return $this->paramName;
  }

  /**
   * @return VO_RequestParamName
   */
  public function getParamPath(): string
  {
    return $this->paramPath;
  }

  /**
   * @return BaseParamErrorCode
   */
  public function getErrorCode(): BaseParamErrorCode|null
  {
    return $this->errorCode;
  }

}
