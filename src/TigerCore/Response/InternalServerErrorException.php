<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;
use TigerCore\Payload\ICanGetPayloadRawData;

class InternalServerErrorException extends BaseResponseException {
  public function __construct(ICanGetPayloadRawData|string $payload = '') {
    parent::__construct( IResponse::S500_INTERNAL_SERVER_ERROR, $payload);
  }

}