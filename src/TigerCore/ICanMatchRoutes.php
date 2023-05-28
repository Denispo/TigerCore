<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;

interface ICanMatchRoutes  {

  /**
   * @param IRequest $httpRequest
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function runMatch(IRequest $httpRequest):ICanGetPayloadRawData;


}