<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class NotFoundException extends BaseResponseException {
  public function __construct(ICanGetPayload $payload, string $message = '') {
    parent::__construct($payload, $message, IResponse::S404_NOT_FOUND);
  }

}