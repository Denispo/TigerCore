<?php

namespace TigerCore\Payload;

interface ICanGetPayloadRawData {
  public function getPayloadRawData():array|object;
}