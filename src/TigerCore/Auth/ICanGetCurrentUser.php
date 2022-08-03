<?php

namespace TigerCore\Auth;

interface ICanGetCurrentUser {

  public function GetCurrentUser():ICurrentUser;

}