<?php

namespace TigerCore\Response;


/**
 *  A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.
 */
#[ResponseCode(405)]
class S405_MethodNotAllowedException extends Base_4xx_RequestException {

  /**
   *   *
   * @param array $allowedMethods
   * @param string|int $customErrorId
   * @param array $customData
   */
  public function __construct(private array $allowedMethods = [], string|int $customErrorId = '', array $customData = []) {
    parent::__construct($customErrorId,  $customData);
  }

  /**
   * @return array
   */
  public function getAllowedMethods(): array {
    return $this->allowedMethods;
  }


}