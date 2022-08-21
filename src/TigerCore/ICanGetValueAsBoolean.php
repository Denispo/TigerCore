<?php

namespace TigerCore;


interface ICanGetValueAsBoolean {

  public function getValueAsBool(bool $defaultValue = false):bool;

}