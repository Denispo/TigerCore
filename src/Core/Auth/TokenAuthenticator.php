<?php

namespace App\ApiModule\Auth;

use Core\ValueObject\VO_TokenPlainStr;
use App\ValueObjects\VO_UserId;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nette\Utils\Arrays;

class TokenAuthenticator implements IAuthenticator {

  private const PUBLIC_KEY = '';
  private const PRIVATE_KEY = '';

  private VO_TokenPlainStr $tokenStr;

  public function __construct(VO_TokenPlainStr $tokenStr) {
    $this->tokenStr = $tokenStr;
  }

  public function getAuthenticatedUser(): AuthenticatedUser|null {
    try {
      JWT::$leeway = 120;
      $decodedToken = (array) JWT::decode($this->tokenStr->getValue(),new Key(self::PUBLIC_KEY,'RS256'));
    } catch (\Exception $e){
      // Token neni validni
      return null;
    }
    try {
      return new AuthenticatedUser(new VO_UserId(Arrays::get($decodedToken, 'uid')));
    } catch (\Exception $e) {
      // V tokenu neni id uzivatele
      return null;
    }
  }
}