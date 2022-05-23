<?php

namespace TigerCore\Auth;


use TigerCore\ValueObject\VO_TokenPublicKey;

interface  ICanGetTokenPublicKey{

  public function getPublicKey():VO_TokenPublicKey;

}