<?php

namespace TigerCore\Response;


abstract class Base_5xx_RequestException extends BaseResponseException {

  public function __construct(string $message = '', array $customData = [], private readonly \Throwable|null $previousException = null)
  {
    parent::__construct(message: $message, customData: $customData,previousException: $this->previousException);
    if (function_exists('\Sentry\captureException') && class_exists('\Sentry\EventHint')) {
      $eventId = \Sentry\captureException($previousException,\Sentry\EventHint::fromArray(['extra' => $customData]));
      $this->setSentryEventId($eventId);
    }
  }



}