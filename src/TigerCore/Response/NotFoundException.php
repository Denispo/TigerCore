<?php

namespace TigerCore\Response;

use Nette\Http\IResponse;

class NotFoundException extends BaseResponseException {
  public function __construct(string $message = '') {
    parent::__construct($message, IResponse::S404_NOT_FOUND);
  }

}