<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class NotFoundException extends BaseResponseException {
  public function __construct(string $message = '', array $customData = []) {
    parent::__construct( IResponse::S404_NOT_FOUND, $message, $customData);
  }

}