<?php

namespace TigerCore\Request;

use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_Email;

interface IOnGetUserByCredentials {

  public function onGetUserIdByCredentials(string $loginName = '', VO_Email|null $loginEmail = null):VO_BaseId;

}