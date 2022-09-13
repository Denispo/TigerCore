<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class MethodNotAllowedException extends BaseResponseException {
  public function __construct(private array $allowedMethods = [], string $message = '', array $customData = []) {
    parent::__construct(IResponse::S405_METHOD_NOT_ALLOWED, $message, $customData);
  }

  /**
   * @return array
   */
  public function getAllowedMethods(): array {
    return $this->allowedMethods;
  }


}