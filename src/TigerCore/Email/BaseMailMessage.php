<?php

namespace TigerCore\Email;

use Nette\Mail\Message;

class BaseMailMessage extends Message {

  public function __construct()
  {
    parent::__construct();
    $this::$defaultHeaders = [
      'MIME-Version' => '1.0',
//      'X-Mailer' => 'Nette Framework',
    ];
  }

  public function setTo(string $email, ?string $name = null):void
  {
    $this->clearHeader('To');
    $this->addTo($email, $name);
  }

}