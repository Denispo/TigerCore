<?php

namespace TigerCore\Requests;


interface ICanGetParamValueAsBoolean {

  public function getValueAsBool(bool $defaultValue = false):bool;

}