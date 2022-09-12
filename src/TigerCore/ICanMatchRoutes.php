<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Response\BaseResponseException;

interface ICanMatchRoutes  {

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return array raw payload
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICanGetCurrentUser $currentUser):array;


}