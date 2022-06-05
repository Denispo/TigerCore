<?php

namespace TigerCore\Requests;


use TigerCore\ValueObject\VO_Timestamp;

interface ICanGetParamValueAsTimestamp {

  public function getValueAsTimestamp():VO_Timestamp;

}