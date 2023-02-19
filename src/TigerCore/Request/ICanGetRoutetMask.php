<?php

namespace TigerCore\Request;

use TigerCore\ValueObject\VO_RouteMask;

interface ICanGetRoutetMask {

  public function getMask():VO_RouteMask;

}