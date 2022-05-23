<?php

namespace TigerCore\Request;

use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_Email;
use TigerCore\ValueObject\VO_Password;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Constants\PasswordValidity;
use TigerCore\Response\InvalidCredentialsException;

abstract class BaseLoginUserRequest extends BaseFormRequest implements ICanMatch, IOnAddToPayload {

  #[RequestData('name')]
  public mixed $userName;

  #[RequestData('email')]
  public mixed $userEmail;

  #[RequestData('password')]
  public mixed $userPassword;

  //----------------------------

  protected abstract function onGetUserIdByCredentials(string $loginName = '', VO_Email|null $loginEmail = null):VO_BaseId;

  protected abstract function onVerifyPassword(VO_Password $password, VO_BaseId $userId):PasswordValidity;

  protected abstract function onLoginComplete(VO_BaseId $userId):void;

  public function onMatch(ICurrentUser $currentUser):void {

    $userEmail = new VO_Email($this->userName);
    if (!$userEmail->isValid()) {
      $userEmail = new VO_Email($this->userEmail);
    }
    if (!$userEmail->isValid()) {
      $userEmail = null;
    }

    $userId = $this->onGetUserIdByCredentials($this->userName, $userEmail);

    if (!$userId->isValid()) {
      throw new InvalidCredentialsException();
    }

    $passwordValidity = $this->onVerifyPassword(new VO_Password($this->userPassword), $userId);

    if ($passwordValidity->IsSetTo(PasswordValidity::PWD_INVALID)) {
      if ($this instanceof IOnInvalidPassword) {
        $this->onInvalidPassword($userId);
      }
      throw new InvalidCredentialsException();
    }

    $this->onLoginComplete($userId);

  }

}