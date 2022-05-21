<?php

namespace Core\Request;

use Core\Response\BaseResponseException;
use Core\ValueObject\VO_BaseId;

interface IOnLoginComplete {

  /**
   * @param VO_BaseId $userId
   * @return void
   * @throws BaseResponseException
   */
  public function onLoginComplete(VO_BaseId $userId):void;

}