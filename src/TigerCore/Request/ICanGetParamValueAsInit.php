<?php

namespace TigerCore\Requests;


interface ICanGetParamValueAsInit {

  public function getValueAsInt(int $defaultValue = 0):int;

}