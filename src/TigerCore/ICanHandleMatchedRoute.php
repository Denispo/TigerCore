<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;

interface ICanHandleMatchedRoute  {

  /**
   * @param array $params
   * @param IRequest $request
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function handleMatchedRoute(array $params, IRequest $request):ICanGetPayloadRawData;

}