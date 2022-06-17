<?php

namespace TigerCore\Requests;


interface ICanGetParamValueAsString {

  public function getValueAsString(string $defaultValue = ''):string;

}