<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class NotFoundException extends BaseResponseException {
  public function __construct(ICanGetPayload|string $payload = '') {
    parent::__construct( IResponse::S404_NOT_FOUND, $payload);
  }

}