<?php

namespace TigerCore\Payload;


interface IBasePayload extends ICanGetPayloadRawData {

  public function getPayloadRawData():array;

}