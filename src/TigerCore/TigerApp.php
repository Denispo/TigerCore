<?php

namespace TigerCore;

use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\ICanAddToPayload;
use Nette\Http\IRequest;

abstract class TigerApp extends BaseApp implements ICanAddToPayload {

  public function run(IRequest $httpRequest, ICurrentUser $currentUser) {
    $router = $this->onGetRouter();
    if ($router) {
      $router->match($httpRequest, $currentUser);
    }
  }

  protected abstract function onGetRouter():ICanMatchRoutes;
}