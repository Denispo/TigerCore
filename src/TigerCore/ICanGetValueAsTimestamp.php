<?php

namespace TigerCore;


use TigerCore\ValueObject\VO_Timestamp;

interface ICanGetValueAsTimestamp {

  public function getValueAsTimestamp():VO_Timestamp;

}