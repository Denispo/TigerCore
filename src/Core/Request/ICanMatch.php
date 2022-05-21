<?php

namespace Core\Request;

use Core\Auth\ICurrentUser;
use Core\Response\BaseResponseException;

interface ICanMatch {

  /**
   * @param ICurrentUser $currentUser
   * @return void
   * @throws BaseResponseException
   */
  public function onMatch(ICurrentUser $currentUser):void;

}