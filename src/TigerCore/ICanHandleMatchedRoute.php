<?php

namespace TigerCore;

use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;

interface ICanHandleMatchedRoute  {

  /**
   * @param array $params Route mask params
   * @param mixed $customData Custom data passed to addRoute()
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function handleMatchedRoute(array $params, mixed $customData):ICanGetPayloadRawData;

}