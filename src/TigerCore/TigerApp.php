<?php

namespace TigerCore;

use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\ICanAddPayload;
use Nette\Http\IRequest;

abstract class TigerApp extends BaseApp implements ICanAddPayload {

  public function run(IRequest $httpRequest, ICurrentUser $currentUser) {
    $router = $this->onGetRouter();
    if ($router) {
      $router->match($httpRequest, $currentUser);
    }
  }

  protected abstract function onGetRouter():ICanMatchRoutes;
}