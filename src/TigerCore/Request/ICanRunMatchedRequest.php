<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;

interface ICanRunMatchedRequest {

  /**
   * @throws BaseResponseException
   */
  public function runMatchedRequest(MatchedRequestData $requestData):void;

}