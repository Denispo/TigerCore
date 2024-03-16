<?php

namespace TigerCore;

use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\S404_NotFoundException;
use TigerCore\ValueObject\VO_HttpRequestMethod;

interface ICanMatchRoutes  {

  /**
   * @param VO_HttpRequestMethod $requestMethod IRequest->getMethod()
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return ICanGetPayloadRawData|null
   * @throws BaseResponseException
   */
  public function runMatch(VO_HttpRequestMethod $requestMethod, string $requestUrlPath):ICanGetPayloadRawData|null;


  /** Run this method for preflight requests. Preflight is when request header is set to "OPTIONS"
   * Method ignores all Handlers provided in addRoute(). I.e. handlers can be null
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return string[] Array of allowed headers $requestUrlPath can be called with
   * @throws S404_NotFoundException
   */
  public function runMatchPreflight(string $requestUrlPath):array;

}