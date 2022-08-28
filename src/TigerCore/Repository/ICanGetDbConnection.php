<?php

namespace TigerCore\Repository;

use Nette\Database\Connection;

interface ICanGetDbConnection {

  public function GetDbConnection():Connection;

}