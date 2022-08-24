<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanGetPayloadData;

interface ICanRunMatchedRequest {

  /**
   * @throws BaseResponseException
   */
  public function runMatchedRequest(MatchedRequestData $requestData):ICanGetPayloadData;

}