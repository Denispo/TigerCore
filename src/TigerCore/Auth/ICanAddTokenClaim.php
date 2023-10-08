<?php

namespace TigerCore\Auth;

interface ICanAddTokenClaim {

  public function addClaim(string $name, string|int|array $value):void;

}