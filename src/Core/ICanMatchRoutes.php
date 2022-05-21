<?php

namespace Core;

use Nette\Http\IRequest;

interface ICanMatchRoutes  {

  public function match(IRequest $httpRequest);


}