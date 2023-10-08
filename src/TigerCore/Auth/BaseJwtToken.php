<?php

namespace TigerCore\Auth;

use ArrayAccess;
use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_Timestamp;
use TigerCore\ValueObject\VO_TokenPlainStr;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use TigerCore\ValueObject\VO_TokenPrivateKey;
use TigerCore\ValueObject\VO_TokenPublicKey;

class BaseJwtToken{

  /**
   * @param VO_TokenPublicKey $publicKey
   * @param VO_TokenPlainStr $tokenStr
   * @param string $algorithm
   * @return BaseTokenPayload
   * @throws InvalidTokenException
   */
  public static function decodeToken(VO_TokenPublicKey $publicKey, VO_TokenPlainStr $tokenStr, string $algorithm = 'RS256'): BaseTokenPayload {
    try {
      $data = (array) JWT::decode($tokenStr->getValueAsString(), new Key($publicKey->getValueAsString(), $algorithm));
    } catch (\InvalidArgumentException|\DomainException|\UnexpectedValueException|SignatureInvalidException|BeforeValidException|ExpiredException|\TypeError $e) {
      switch (get_class($e)) {
        case \InvalidArgumentException::class:{
          $error = new TokenError(TokenError::ERR_INVALID_ARGUMENT);
          break;
        }
        case \DomainException::class:{
          $error = new TokenError(TokenError::ERR_INVALID_DOMAIN);
          break;
        }
        case \UnexpectedValueException::class:{
          $error = new TokenError(TokenError::ERR_UNEXPECTED_VALUE);
          break;
        }
        case SignatureInvalidException::class:{
          $error = new TokenError(TokenError::ERR_INVALID_SIGNATURE);
          break;
        }
        case BeforeValidException::class:{
          $error = new TokenError(TokenError::ERR_BEFORE_VALID);
          break;
        }
        case ExpiredException::class:{
          $error = new TokenError(TokenError::ERR_EXPIRED);
          break;
        }
        default:{
          $error = new TokenError(TokenError::ERR_NA);
          break;
        }
      }
      throw new InvalidTokenException($error, $e->getMessage(), $e->getCode(), $e->getPrevious());
    }

    return new BaseTokenPayload($data);
  }

  /**
   * @param VO_TokenPrivateKey $privateKey
   * @param VO_Duration $duration
   * @param array $payload Payload except 'iat' and 'exp'. There are added automatically based on server time and $duration
   * @param string $algorithm
   * @return VO_TokenPlainStr
   * @throws InvalidArgumentException
   */
  public static function encodeToken(VO_TokenPrivateKey $privateKey, VO_Duration $duration, array $payload = [], string $algorithm = 'RS256'):VO_TokenPlainStr {

    $time = time();
    $expirationDate = (new VO_Timestamp($time))->addDuration($duration);

    try {
      $tokenStr = JWT::encode(
        array_merge(
          $payload,
          [
            'iat' => $time, // issued at
            'exp' => $expirationDate->getValueAsInt(), // epiration time
          ]),
        $privateKey->getValueAsString(),
        $algorithm
      );
    } catch (\DomainException $e) {
      throw new  InvalidArgumentException();
    }


    return new VO_TokenPlainStr($tokenStr);

  }

}