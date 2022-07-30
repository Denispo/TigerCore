<?php

namespace TigerCore\Request;

use Nette\Http\IRequest;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddPayload;

interface ICanRunMatchedRequest {

  /**
   * @param ICurrentUser $currentUser
   * @param ICanAddPayload $payload
   * @param IRequest $httpRequest
   * @throws BaseResponseException
   */
  public function runMatchedRequest(ICurrentUser $currentUser, ICanAddPayload $payload, IRequest $httpRequest):void;

}