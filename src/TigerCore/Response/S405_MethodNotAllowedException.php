<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class S405_MethodNotAllowedException extends BaseResponseException {

  /**
   * A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.
   * @param array $allowedMethods
   * @param string $message
   * @param array $customData
   */
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