<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\BaseResponseException;

interface ICanMatchRoutes  {

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICanGetCurrentUser $currentUser):ICanGetPayloadRawData;


}