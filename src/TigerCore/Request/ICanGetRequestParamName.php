<?php

declare(strict_types=1);

namespace TigerCore\Requests;

interface ICanGetRequestParamName {

  public function getParamName():string;

}