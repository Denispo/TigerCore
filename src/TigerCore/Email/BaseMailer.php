<?php

namespace TigerCore\Email;

abstract class BaseMailer implements ICanSendEmail {
  protected abstract function onAfterEmailHasBeenSent(BaseMailMessage $mail);
}