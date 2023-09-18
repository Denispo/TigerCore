<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;
use TigerCore\ValueObject\VO_HttpRequestMethod;

interface ICanMatchRoutes  {

  /**
   * @param VO_HttpRequestMethod $requestMethod IRequest->getMethod()
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function runMatch(VO_HttpRequestMethod $requestMethod, string $requestUrlPath):ICanGetPayloadRawData;


}