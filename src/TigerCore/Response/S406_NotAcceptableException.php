<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class S406_NotAcceptableException extends Base_4xx_RequestException {

  /**
   * The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.
   * @param string $message
   * @param array $customData
   */
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct(IResponse::S406_NOT_ACCEPTABLE, $message, $customData);
  }


}