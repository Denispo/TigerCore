<?php

namespace TigerCore\Email;

use Nette\Mail\Mailer;
use Nette\Mail\Message;
use TigerCore\ValueObject\VO_Email;

class BaseMailMessage {

  protected Message $message;

  /**
   * If $subject is not set then subject will be set from <title> tag from Html message body
   * @param VO_Email $fromEmail
   * @param string $subject
   * @param string|null $fromName
   */
  public function __construct(VO_Email $fromEmail, string $fromName = null, string $subject = '')
  {
    $this->message = new Message();
    $this->message::$defaultHeaders = [
      'MIME-Version' => '1.0',
//      'X-Mailer' => 'Nette Framework',
    ];
    $this->message->setFrom($fromEmail->getValueAsString(), $fromName);
    $this->message->setSubject($subject);
  }

  public function send(VO_Email $to, Mailer $mailer):void
  {
    $this->message->clearHeader('To');
    $this->message->addTo($to->getValueAsString());
    $mailer->send($this->message);
  }

  public function setHtmlBody(string $htmlBody):void
  {
    $this->message->setHtmlBody($htmlBody);
  }

  public function getSubject():string
  {
    return $this->message->getSubject();
  }

  public function getTo():string|array
  {
    return $this->message->getHeader('To');
  }

  public function getFrom():string
  {
    return current($this->message->getFrom()?? ['']);
  }

  public function getBodyHtml():string
  {
    return $this->message->getHtmlBody();
  }

  public function getBodyPlainText():string
  {
    return $this->message->getBody();
  }

}