<?php

namespace TigerCore\Request;


use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\ICanAddPayload;

class MatchedRequestData {

  public function __construct(
    private ICanGetCurrentUser $currentUser,
    private ICanAddPayload     $payloadContainer,
    private IRequest           $httpRequest
  ) {

  }

  public function getHttpRequest():IRequest {
    return $this->httpRequest;
  }

  public function getPayloadContainer():ICanAddPayload {
    return $this->payloadContainer;
  }

  public function getCurrentUser():ICurrentUser {
    return $this->currentUser->getCurrentUser();

  }

}