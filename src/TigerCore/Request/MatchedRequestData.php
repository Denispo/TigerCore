<?php

namespace TigerCore\Request;


use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Payload\IBasePayload;
use TigerCore\Response\ICanAddPayload;

class MatchedRequestData {

  public function __construct(
    private ICanGetCurrentUser $currentUser,
    private ICanAddPayload     $payload,
    private IRequest           $httpRequest
  ) {

  }

  public function getHttpRequest():IRequest {
    return $this->httpRequest;
  }

  public function addToPayload(IBasePayload $payload):void {
    $this->payload->addPayload($payload);

  }

  public function getCurrentUser():ICurrentUser {
    return $this->currentUser->getCurrentUser();

  }

}