<?php

namespace TigerCore\Auth;

interface ICanGetTokenClaims {

  /**
   * @return array<string, mixed>
   */
  public function getClaims(): array;

}