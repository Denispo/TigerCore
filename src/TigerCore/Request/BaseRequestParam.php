<?php

declare(strict_types=1);

namespace TigerCore\Requests;

use TigerCore\ValueObject\VO_RequestParamName;

abstract class BaseRequestParam implements ICanGetRequestParamName {

  private bool $isSet;
  private VO_RequestParamName $paramName;

  public function __construct(ICanGetRequestParamName $paramName, mixed $paramValue) {
    $this->paramName = $paramName->getParamName();
    $this->isSet = $this->onSetValue($paramValue);
  }

  protected abstract function onSetValue(mixed $paramValue):bool;

  public function isSet():bool {
    return $this->isSet;
  }

  public function getParamName():VO_RequestParamName
  {
    return $this->paramName;
  }

}