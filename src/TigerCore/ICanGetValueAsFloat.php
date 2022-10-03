<?php

namespace TigerCore;


interface ICanGetValueAsFloat {

  public function getValueAsFloat(float $defaultValue = 0):float;

}