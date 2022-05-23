<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\ValueObject\VO_BaseId;

interface IOnInvalidPassword {

  /**
   * @param VO_BaseId $userId
   * @return void
   * @throws BaseResponseException
   */
  public function onInvalidPassword(VO_BaseId $userId):void;

}