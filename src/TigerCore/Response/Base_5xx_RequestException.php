<?php

namespace TigerCore\Response;


abstract class Base_5xx_RequestException extends BaseResponseException {

  public function __construct(string $message = '', array $customData = [], private readonly \Throwable|null $previousException = null)
  {
    parent::__construct(message: $message, customData: $customData,previousException: $this->previousException);
    if (function_exists('\Sentry\captureException') && class_exists('\Sentry\EventHint')) {

      $data['custom_data'] = $this->getCustomData();
      $data['original_exception'] = [
        'class' => get_class($this),
        'message' => $this->message,
        'file' => $this->file,
        'line' => $this->line,
        'trace' =>  $this->getTrace()
      ];


      $eventId = \Sentry\captureException($previousException ? $previousException : $this,\Sentry\EventHint::fromArray(['extra' => $data]));
      $this->setSentryEventId($eventId);
    }
  }



}