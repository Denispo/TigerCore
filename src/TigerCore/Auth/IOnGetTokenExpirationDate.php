<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_Timestamp;

interface IOnGetTokenExpirationDate {

  public function onGetTokenExpirationDate():VO_Timestamp;

}