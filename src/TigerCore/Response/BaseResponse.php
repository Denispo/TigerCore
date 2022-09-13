<?php

namespace TigerCore\Response;


use TigerCore\Payload\IAmPayloadContainer;

abstract class BaseResponse implements IAmPayloadContainer {

  protected array $payload = [];

}