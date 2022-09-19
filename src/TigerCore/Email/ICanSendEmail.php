<?php

namespace TigerCore\Email;

interface ICanSendEmail {

  /**
   * @param BaseMailMessage $mailMessage
   * @return void
   * @throws CanNotSendEmailException
   */
  public function send(BaseMailMessage $mailMessage): void;
}