<?php

namespace TigerCore\Request;

use TigerCore\Response\BaseResponseException;
use TigerCore\ValueObject\VO_BaseId;

interface IOnLoginComplete {

  /**
   * @param VO_BaseId $userId
   * @return void
   * @throws BaseResponseException
   */
  public function onLoginComplete(VO_BaseId $userId):void;

}