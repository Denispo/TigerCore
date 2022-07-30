<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddPayload;

interface IOnAddToPayload {

  /**
   * @param ICanAddPayload $payload
   * @return void
   * @throws BaseResponseException
   */
  public function onAddPayload(ICanAddPayload $payload):void;

}