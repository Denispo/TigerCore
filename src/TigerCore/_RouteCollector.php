<?php

namespace TigerCore;

use TigerCore\Request\BaseRequest;

class _RouteCollector implements ICanAddRequest {

  /**
   * @var BaseRequest[]
   */
  private array $routes;

  public function add(BaseRequest $request) {
    $this->routes[] = $request;
  }

  /**
   * @return BaseRequest[]
   */
  public function getRoutes():array {
    return $this->routes;
  }
}