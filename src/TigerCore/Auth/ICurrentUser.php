<?php

namespace TigerCore\Auth;

interface ICurrentUser {

  public function isLoggedIn():bool;

}