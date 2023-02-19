<?php

namespace TigerCore;

use TigerCore\ValueObject\VO_RouteMask;

interface ICanHandleMatchedRoute  {

  public function handleMatchedRoute():void;

}