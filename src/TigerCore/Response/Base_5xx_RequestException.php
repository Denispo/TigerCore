<?php

namespace TigerCore\Response;


abstract class Base_5xx_RequestException extends BaseResponseException {

   public function __construct(string $message = '', array $customData = [], private readonly \Throwable|null $previousException = null)
   {
      parent::__construct(message: $message, customData: $customData,previousException: $this->previousException);

      $captureExceptionFunction = '\Sentry\captureException';
      $eventHintClass   = '\Sentry\EventHint';

      if (function_exists($captureExceptionFunction) && class_exists($eventHintClass)) {

         $data['custom_data'] = $this->getCustomData();
         $data['original_exception'] = [
            'class' => get_class($this),
            'message' => $this->message,
            'file' => $this->file,
            'line' => $this->line,
            'trace' =>  $this->getTrace()
         ];


         $eventId = $captureExceptionFunction($previousException ? $previousException : $this,$eventHintClass::fromArray(['extra' => $data]));
         if (is_string($eventId)) {
            $this->setSentryEventId($eventId);
         }
      }
   }



}