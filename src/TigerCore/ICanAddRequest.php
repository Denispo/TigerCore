<?php

namespace TigerCore;

use TigerCore\Request\ICanGetRequestMask;

interface ICanAddRequest  {

  /**
   * @param string|string[] $method GET,POST,PUT,DELETE etc.
   * @param ICanGetRequestMask $request
   * @return void
   */
  public function addRequest(string|array $method, ICanGetRequestMask $request):void;

}