<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class S400_BadRequestException extends Base_4xx_RequestException {

  /**
   * The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, size too large, invalid request message framing, or deceptive request routing).
   * @param string $message
   * @param array $customData
   */
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct(IResponse::S400_BAD_REQUEST, $message, $customData);
  }

}