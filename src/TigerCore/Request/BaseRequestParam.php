<?php

namespace TigerCore\Requests;

abstract class BaseRequestParam {

  private bool $wasSet;

  public function __construct(private string $paramName, mixed $paramValue) {
    $this->wasSet = $this->onSetValue($paramValue);
  }

  public abstract function onSetValue(mixed $paramValue):bool;

  public function valueWasSet():bool {
    return $this->wasSet;
  }

  public function getParamName():string
  {
    return $this->paramName;
  }

}