<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\Payload\ICanGetPayloadRawData;

interface ICanRunMatchedRequest {

  /**
   * @throws BaseResponseException
   */
  public function runMatchedRequest(MatchedRequestData $requestData):ICanGetPayloadRawData;

}