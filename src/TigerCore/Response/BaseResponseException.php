<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class BaseResponseException extends \Exception implements ICanGetPayload {


  public function __construct(private ICanGetPayload|null $payload = null, string $message = '',  int $httpResponseCode = IResponse::S400_BAD_REQUEST) {
    parent::__construct($message, $httpResponseCode);
  }

  public function getPayload(): array {
    if ($this->payload) {
      return $this->payload->getPayload();
    } else {
      return [];
    }
  }
}