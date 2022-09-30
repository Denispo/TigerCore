<?php

namespace TigerCore\Response;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ResponseCode
{
  public function __construct(public int $responseCode)
  {
  }

}