<?php

namespace Core\Auth;


use Core\ValueObject\VO_TokenPublicKey;

interface  ICanGetTokenPublicKey{

  public function getPublicKey():VO_TokenPublicKey;

}