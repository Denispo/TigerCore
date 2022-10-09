<?php

namespace TigerCore\Request;


use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\IAmCurrentUser;
use TigerCore\Payload\IAmPayloadContainer;
use TigerCore\Request\Validator\InvalidRequestParam;

class MatchedRequestData {

  /**
   * @param ICanGetCurrentUser $currentUser
   * @param IAmPayloadContainer $payloadContainer
   * @param IRequest $httpRequest
   * @param InvalidRequestParam[] $invalidParams
   */
  public function __construct(
    private ICanGetCurrentUser    $currentUser,
    private IAmPayloadContainer   $payloadContainer,
    private IRequest              $httpRequest,
    private array                 $invalidParams,
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

  /**
   * Key is param name
   * @return InvalidRequestParam[]
   */
  public function getInvalidParams(): array
  {
    return $this->invalidParams;
  }

}