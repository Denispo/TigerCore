<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class S404_NotFoundException extends Base_4xx_RequestException {
  /**
   * The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible.
   * @param string $message
   * @param array $customData
   */
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct( IResponse::S404_NOT_FOUND, $message, $customData);
  }

}