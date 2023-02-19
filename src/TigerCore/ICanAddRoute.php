<?php

namespace TigerCore;

use TigerCore\ValueObject\VO_RouteMask;

interface ICanAddRoute  {


  /**
   * @param string|array $method
   * @param VO_RouteMask $mask
   * @param ICanHandleMatchedRoute $handler
   * @return void
   */
  public function addRoute(string|array $method, VO_RouteMask $mask, ICanHandleMatchedRoute $handler):void;

}