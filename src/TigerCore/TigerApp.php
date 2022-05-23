<?php

namespace TigerCore;

use TigerCore\Response\ICanAddToPayload;
use Nette\Http\IRequest;

abstract class TigerApp extends BaseApp implements ICanAddToPayload {

  public function run(IRequest $httpRequest) {
    $router = $this->onGetRouter();
    if ($router) {
      $router->match($httpRequest);
    }
  }

  protected abstract function onGetRouter():ICanMatchRoutes;
}