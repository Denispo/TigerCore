<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class S500_InternalServerErrorException extends BaseResponseException {

  /**
   * A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
   * @param string $message
   * @param array $customData
   */
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct( IResponse::S500_INTERNAL_SERVER_ERROR, $message, $customData);
  }

}