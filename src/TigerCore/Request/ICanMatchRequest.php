<?php

namespace TigerCore\Request;

use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddToPayload;

interface ICanMatchRequest {

  /**
   * @param ICurrentUser $currentUser
   * @param ICanAddToPayload $payload
   * @return void
   * @throws BaseResponseException
   */
  public function onMatch(ICurrentUser $currentUser, ICanAddToPayload $payload):void;

}