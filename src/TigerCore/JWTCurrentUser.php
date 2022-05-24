<?php

namespace TigerCore;

use TigerCore\ValueObject\VO_TokenPlainStr;
use TigerCore\Auth\BaseCurrentUser;
use TigerCore\Auth\ICurrentUser;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nette\Utils\Arrays;

class JWTCurrentUser extends BaseCurrentUser implements ICurrentUser {

  private static self $currentUser;

  private array $decodedToken = [];

  public static function getInstance(VO_TokenPlainStr $tokenStr):static {
    if (!self::$currentUser) {
      self::$currentUser = new self($tokenStr);
    }
    return self::$currentUser;
  }

  public static function generateAccessToken(VO_TokenPlainStr $refreshTokenStr, $validDurationinSeconds = 10 * 60 * 60):string {
    try {
      JWT::$leeway = 120;
      $token = (array) JWT::decode($refreshTokenStr->getValue(),new Key(self::PUBLIC_KEY,'RS256'));
    } catch (\Exception $e){
      // Token neni validni
      return '';
    }
    $userId = Arrays::get($token,'uid');
    if (!$userId) {
      return '';
    }
    $data = Arrays::get($token,'data',[]);
    return JWT::encode([
      'uid' => $userId,
      'iat' => time(),
      'exp' => time()+$validDurationinSeconds,
      'data' => $data
    ], self::PRIVATE_KEY, 'RS256');
  }

  public static function generateRefreshToken(string|int $userId, array $claims, $validDurationinSeconds = 5* 24 * 60 * 60):string {
    JWT::$leeway = 120;
    return JWT::encode([
      'uid' => $userId,
      'iat' => time(),
      'exp' => time()+$validDurationinSeconds,
      'data' => $claims
    ], self::PRIVATE_KEY, 'RS256');
  }

  public function __construct(private VO_TokenPlainStr $tokenStr) {
    try {
      JWT::$leeway = 120;
      $this->decodedToken = (array) JWT::decode($this->tokenStr->getValue(),new Key(self::PUBLIC_KEY,'RS256'));
    } catch (\Exception $e){
      // Token neni validni
      return null;
    }
  }

  public function isLoggedIn(): bool {
    return $this->getUserId() != '';
  }

  public function logOut() {
    // TODO: Implement logOut() method.
  }

  public function getUserRole():string {
    return Arrays::get($this->decodedToken, 'role', '');

  }

  public function getUserId():string|int {
    return Arrays::get($this->decodedToken, 'uid') != '';
  }
}