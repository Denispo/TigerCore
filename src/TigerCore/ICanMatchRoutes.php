<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanGetPayloadData;

interface ICanMatchRoutes  {

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return ICanGetPayloadData
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICanGetCurrentUser $currentUser):ICanGetPayloadData;


}