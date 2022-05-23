<?php

namespace TigerCore\Request;

use TigerCore\ValueObject\VO_RouteMask;

interface ICanGetRequestMask {

  public function getMask():VO_RouteMask;

}