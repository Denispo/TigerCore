<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class BaseResponseException extends \Exception implements ICanGetPayload {

  public function __construct(private ICanGetPayload $payload, string $message = '',  int $httpResponseCode = IResponse::S400_BAD_REQUEST) {
    parent::__construct($message, $httpResponseCode);
  }

  public function getPayload(): array {
    return $this->payload->getPayload();
  }
}