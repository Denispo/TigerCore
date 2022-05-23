<?php

namespace TigerCore\Auth;

interface ICanGetCurentUser {

  public function onGetCurrentUser():ICurrentUser;

}