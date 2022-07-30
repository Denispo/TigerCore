<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\BaseResponseException;

interface ICanMatchRoutes  {

  /**
   * @param IRequest $httpRequest
   * @param ICurrentUser $currentUser
   * @return void
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICurrentUser $currentUser):void;


}