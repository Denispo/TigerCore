<?php

namespace TigerCore\Auth;

interface ICanAddCustomTokenClaim {

  public function addCustomClaim(string $name, string $value):void;

}