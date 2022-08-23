<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanGetPayload;

interface ICanRunMatchedRequest {

  /**
   * @throws BaseResponseException
   */
  public function runMatchedRequest(MatchedRequestData $requestData):ICanGetPayload;

}