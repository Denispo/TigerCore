<?php

namespace Core\Response;


abstract class BaseResponse implements ICanAddToPayload{

  protected array $payload = [];

}