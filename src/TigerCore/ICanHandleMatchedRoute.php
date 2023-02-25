<?php

namespace TigerCore;

use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;

interface ICanHandleMatchedRoute  {

  /**
   * @param array $params
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function handleMatchedRoute(array $params):ICanGetPayloadRawData;

}