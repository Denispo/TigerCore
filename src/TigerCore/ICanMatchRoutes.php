<?php

namespace TigerCore;

use Nette\Http\IRequest;

interface ICanMatchRoutes  {

  public function match(IRequest $httpRequest);


}