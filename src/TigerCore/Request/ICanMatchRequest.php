<?php

namespace TigerCore\Request;

use Nette\Http\IRequest;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddToPayload;

interface ICanMatchRequest {

  /**
   * @param ICurrentUser $currentUser
   * @param ICanAddToPayload $payload
   * @param IRequest $httpRequest
   * @return void
   * @throws BaseResponseException
   */
  public function onMatch(ICurrentUser $currentUser, ICanAddToPayload $payload, IRequest $httpRequest):void;

}