<?php

namespace Core\Auth;


use Core\ValueObject\VO_TokenPrivateKey;

interface  ICanGetTokenPrivateKey{

  public function getPrivateKey():VO_TokenPrivateKey;

}