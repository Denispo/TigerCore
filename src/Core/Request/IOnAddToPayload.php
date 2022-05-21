<?php

namespace Core\Request;

use Core\Response\BaseResponseException;
use Core\Response\ICanAddToPayload;

interface IOnAddToPayload {

  /**
   * @param ICanAddToPayload $payload
   * @return void
   * @throws BaseResponseException
   */
  public function onAddPayload(ICanAddToPayload $payload):void;

}