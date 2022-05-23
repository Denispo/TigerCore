<?php

namespace TigerCore\Auth;


use TigerCore\ValueObject\VO_TokenPrivateKey;

interface  ICanGetTokenPrivateKey{

  public function getPrivateKey():VO_TokenPrivateKey;

}