<?php

namespace Core\Request;

use Core\ValueObject\VO_RouteMask;

interface ICanGetRequestMask {

  public function getMask():VO_RouteMask;

}