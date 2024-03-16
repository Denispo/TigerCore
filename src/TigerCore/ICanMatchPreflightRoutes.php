<?php

namespace TigerCore;

use TigerCore\Response\S404_NotFoundException;

interface ICanMatchPreflightRoutes  {

  /** Run this method for preflight requests. Preflight is when request header is set to "OPTIONS"
   * Method ignores all Handlers provided in addRoute(). I.e. handlers can be null
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return string[] Array of allowed headers $requestUrlPath can be called with
   * @throws S404_NotFoundException
   */
  public function runMatchPreflight(string $requestUrlPath):array;


}