<?php

namespace Core\Request;

use Core\Response\BaseResponseException;
use Core\ValueObject\VO_BaseId;

interface IOnInvalidPassword {

  /**
   * @param VO_BaseId $userId
   * @return void
   * @throws BaseResponseException
   */
  public function onInvalidPassword(VO_BaseId $userId):void;

}