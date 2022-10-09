<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

interface ICanValidateTimestampRequestParam {

  public function isRequestParamValid(ICanGetValueAsTimestamp $requestParam):bool;

}
