<?php

namespace TigerCore\Auth;

interface ICanGetTokenClaims {

  public function getClaims(): array;

}