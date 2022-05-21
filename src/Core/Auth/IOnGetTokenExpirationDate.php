<?php

namespace Core\Auth;

use Core\ValueObject\VO_Timestamp;

interface IOnGetTokenExpirationDate {

  public function onGetTokenExpirationDate():VO_Timestamp;

}