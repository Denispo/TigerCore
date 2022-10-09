<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsTimestamp;

interface ICanValidateTimestampRequestParam {

  public function checkRequestParamValidity(ICanGetValueAsTimestamp $requestParam):BaseParamErrorCode|null;

}
