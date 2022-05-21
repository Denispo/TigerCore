<?php

namespace Core;

use Nette\Http\IRequest;

class TigerHttpRequest {

  public function __construct(private IRequest $httpRequest) {

  }

  protected function getPostParam(string $paramName, $default = null):int|string|array|null {
    return $this->httpRequest->getPost($paramName) ?? $default;
  }

  protected function getQueryParam(string $paramName, $default = null):int|string|array|null {
    return $this->httpRequest->getQuery($paramName) ?? $default;
  }

  public function isAjax(): bool {
    return $this->httpRequest->isAjax();
  }
}