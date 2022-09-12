<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;
use TigerCore\Payload\ICanGetPayloadRawData;

class NotFoundException extends BaseResponseException {
  public function __construct(ICanGetPayloadRawData|string $payload = '') {
    parent::__construct( IResponse::S404_NOT_FOUND, $payload);
  }

}