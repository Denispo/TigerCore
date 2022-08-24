<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class NotFoundException extends BaseResponseException {
  public function __construct(ICanGetPayloadData|string $payload = '') {
    parent::__construct( IResponse::S404_NOT_FOUND, $payload);
  }

}