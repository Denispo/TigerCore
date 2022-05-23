<?php

namespace TigerCore;

use TigerCore\Request\ICanGetRequestMask;

interface ICanAddRequest  {

  public function add(ICanGetRequestMask $request);

}