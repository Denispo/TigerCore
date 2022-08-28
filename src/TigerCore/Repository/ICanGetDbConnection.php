<?php

namespace TigerCore\Repository;

use Nette\Database\Connection;

interface ICanGetDbConnection {

  public function getDbConnection():Connection;

}