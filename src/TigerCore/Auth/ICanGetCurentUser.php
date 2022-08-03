<?php

namespace TigerCore\Auth;

interface ICanGetCurentUser {

  public function GetCurrentUser():ICurrentUser;

}