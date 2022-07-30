<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class BaseResponseException extends \Exception {

  public function __construct(string $message = '', int $httpResponseCode = IResponse::S400_BAD_REQUEST) {
    parent::__construct($message, $httpResponseCode);
  }

}