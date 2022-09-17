<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class S422_UnprocessableEntityException extends BaseResponseException {

  /**
   * The request was well-formed but was unable to be followed due to semantic errors. The client should not repeat this request without modification.
   * @param string $message
   * @param array $customData
   */
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct(IResponse::S422_UNPROCESSABLE_ENTITY, $message, $customData);
  }
}