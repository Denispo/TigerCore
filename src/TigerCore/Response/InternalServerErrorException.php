<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class InternalServerErrorException extends BaseResponseException {
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct( IResponse::S500_INTERNAL_SERVER_ERROR, $message, $customData);
  }

}