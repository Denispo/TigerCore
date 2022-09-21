<?php

namespace TigerCore\Request;


use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\IAmCurrentUser;
use TigerCore\Payload\IAmPayloadContainer;

class MatchedRequestData {

  public function __construct(
    private ICanGetCurrentUser    $currentUser,
    private IAmPayloadContainer   $payloadContainer,
    private IRequest              $httpRequest
  ) {

  }

  public function getHttpRequest():IRequest {
    return $this->httpRequest;
  }

  public function getPayloadContainer():IAmPayloadContainer {
    return $this->payloadContainer;
  }

  public function getCurrentUser():IAmCurrentUser {
    return $this->currentUser->getCurrentUser();

  }

}