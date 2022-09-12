<?php

namespace TigerCore;

use Nette\Http\IRequest;

class BaseApp {

  public function __construct(private IRequest $request) {

  }

  protected function getRequest():IRequest {
    return $this->request;
  }

}