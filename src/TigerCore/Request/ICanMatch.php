<?php

namespace TigerCore\Request;

use TigerCore\Auth\ICurrentUser;
use TigerCore\Response\BaseResponseException;

interface ICanMatch {

  /**
   * @param ICurrentUser $currentUser
   * @return void
   * @throws BaseResponseException
   */
  public function onMatch(ICurrentUser $currentUser):void;

}