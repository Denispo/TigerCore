<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddToPayload;

interface IOnAddToPayload {

  /**
   * @param ICanAddToPayload $payload
   * @return void
   * @throws BaseResponseException
   */
  public function onAddPayload(ICanAddToPayload $payload):void;

}