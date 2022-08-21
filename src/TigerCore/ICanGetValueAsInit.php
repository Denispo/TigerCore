<?php

namespace TigerCore;


interface ICanGetValueAsInit {

  public function getValueAsInt(int $defaultValue = 0):int;

}