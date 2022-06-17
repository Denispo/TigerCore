<?php

declare(strict_types=1);

namespace TigerCore\Requests;

abstract class BaseRequestParam {

  private bool $isSet = false;

  public function __construct(private string $paramName, mixed $paramValue) {
    $this->isSet = $this->onSetValue($paramValue);
  }

  protected abstract function onSetValue(mixed $paramValue):bool;

  public function isSet():bool {
    return $this->isSet;
  }

  public function getParamName():string
  {
    return $this->paramName;
  }

}