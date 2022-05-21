<?php

namespace Core;

use Core\Request\ICanGetRequestMask;

interface ICanAddRequest  {

  public function add(ICanGetRequestMask $request);

}