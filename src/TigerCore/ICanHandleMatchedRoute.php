<?php

namespace TigerCore;

interface ICanHandleMatchedRoute  {

  public function handleMatchedRoute(array $params):void;

}