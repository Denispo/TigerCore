<?php

declare(strict_types=1);

namespace TigerCore\Requests;

use TigerCore\ValueObject\VO_RequestParamName;

interface ICanGetRequestParamName {

  public function getParamName():VO_RequestParamName;

}