<?php

namespace TigerCore;

use Nette\Http\IRequest;
use TigerCore\Auth\ICanGetCurentUser;
use TigerCore\Response\BaseResponseException;

interface ICanMatchRoutes  {

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurentUser $currentUser
   * @return void
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICanGetCurentUser $currentUser):void;


}