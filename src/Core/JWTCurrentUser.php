<?php

namespace Core;

use Core\ValueObject\VO_TokenPlainStr;
use Core\Auth\BaseCurrentUser;
use Core\Auth\ICurrentUser;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nette\Utils\Arrays;

class JWTCurrentUser extends BaseCurrentUser implements ICurrentUser {

  private static self $currentUser;

  private const PRIVATE_KEY = <<<PEPA
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgHpaoLu4bzEHguSYLuClUd39wOCIpBCnX1U6TxjES4EScd+0Z9jO
GCpBFTUf+7YK7EwRbNrYriDn4uC65epPi3FQ2OPJqHULIwFGclAIY0eFvsTe1I2P
hnUzR4SbLeKjSpxrDj9CIYEgHgGwlEzNwXuCpFJwYtX1mx2VapTSjdyvAgMBAAEC
gYA0bXUGOwdaKO/LZ/JeTDiCOONW0vYKNM3CxVNzN1lrGy40PydoXRc5s92Uf/np
jVCnX6gXNlWWwAYVacBu4FrNQRw3C1LsHY4RdrLQRQ4SanIShfUb4msDhSzsfLjK
gujkNQM3d5ut/fsh3JA3nOKkSicGA0I6TKaG1TNaRHFDcQJBAMHYFuLS1gQ3Lwxa
MbwBm1x2v7ihAX8Kpok0WBzXz4TpQzdvIy/u4fIKRNvHFRLaFLg0650tBN3kqJym
WyVnsWsCQQChljHSYMspwu9fmHcN83HZfgPyFBkKfsebUia8PdUrAKJQamVI2Qgl
hBGBQqsvcUzSGVf2j3zCmMLc2ePgft7NAkEAudNXjTYk6IGmXqcQSnUX5LoJ7Qqc
Dpe9Moa1eWEBlR6wyzGFf+v3OjrR7Aabkyjw9+3zeQexRK5xXUq00dTn5QJBAJc9
umI8AMyUzXI/hWeEbhJw9YZ2sz10jqXdTa4xfb1jOYllHGoD4bEjnTLMUOf19z5L
RF7dzJtjWjhWpQiyFx0CQQCCZZh0VtZbJH7PQcWYmrEQT6F1DNiJO2FSh3C1mrO9
/++6Y1ceiC9Y1ub5B5Xq63gFZAPWCiT7NB8SObz+JGZ9
-----END RSA PRIVATE KEY-----
PEPA;


  private const PUBLIC_KEY = <<<PEPA
-----BEGIN PUBLIC KEY-----
MIGeMA0GCSqGSIb3DQEBAQUAA4GMADCBiAKBgHpaoLu4bzEHguSYLuClUd39wOCI
pBCnX1U6TxjES4EScd+0Z9jOGCpBFTUf+7YK7EwRbNrYriDn4uC65epPi3FQ2OPJ
qHULIwFGclAIY0eFvsTe1I2PhnUzR4SbLeKjSpxrDj9CIYEgHgGwlEzNwXuCpFJw
YtX1mx2VapTSjdyvAgMBAAE=
-----END PUBLIC KEY-----
PEPA;


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