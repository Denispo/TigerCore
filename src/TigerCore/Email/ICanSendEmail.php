<?php

namespace TigerCore\Email;

use TigerApi\Email\BaseMailMessage;

interface ICanSendEmail {

  /**
   * @param BaseMailMessage $mailMessage
   * @return void
   * @throws CanNotSendEmailException
   */
  public function send(BaseMailMessage $mailMessage): void;
}