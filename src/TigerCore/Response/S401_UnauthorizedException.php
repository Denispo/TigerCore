<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;


class S401_UnauthorizedException extends BaseResponseException {

  /**
   * Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided. The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource. 401 semantically means "unauthorised", the user does not have valid authentication credentials for the target resource.
   * @param string $message
   * @param array $customData
   */
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct(IResponse::S401_UNAUTHORIZED, $message, $customData);
  }

}