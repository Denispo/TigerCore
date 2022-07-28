<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Auth\ICurrentUser;

interface ICanMatchRoutes  {

  public function match(IRequest $httpRequest, ICurrentUser $currentUser);


}