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
   * @param VO_TokenPlainStr $tokenStr
   * @param VO_TokenPublicKey|array<string, string> $publicKey
   *       array<KeyId,PublicKey> data from "client_x509_cert_url" from firebase-adminsdk JSON (i.e. https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com)
   *       If VO_TokenPublicKey, "key" claims from JWT header will be ignored and VO_TokenPublicKey will be used no matter if it is valid public key for current "key" Id.
   *       If VO_TokenPublicKey is used, caller must quarantee that VO_TokenPublicKey match Private key IdToken was signed with. Otherwise, decode will fail.
   *       If data from endpoint "client_x509_cert_url" is used,  decodeToken() will search for corresponding public key based on "key" claims from JWT header.
   *       Using "client_x509_cert_url" strategy is neccesary if third party IdTokens are enabled (facebook. etc.) because in this scenario we do not know KeyId nor corresponding PublicKey
   * @param string $algorithm $algorithm must match "alg" claims from JWT header
   * @param \stdClass|null $headers reference to $headers object which will be filled by JWT header claims
   * @return BaseTokenPayload
   * @throws InvalidTokenException
   * @throws InvalidArgumentException
   */
  public static function decodeToken(VO_TokenPlainStr $tokenStr, VO_TokenPublicKey|array $publicKey, string $algorithm = 'RS256', \stdClass &$headers = null): BaseTokenPayload {
    try {
      $keyOrKeyArray = [];
      if ($publicKey instanceof VO_TokenPublicKey){
        // If we send $keyOrKeyArray as new Key() into JWT::decode, it will not check "key" claims form JWT header.
        //   i.e. VO_TokenPublicKey is used as public key no matter of "key" claims value. "key" claims is not checked at all.
        $keyOrKeyArray = new Key($publicKey->getValueAsString(), $algorithm);
      } else {
        foreach ($publicKey as $key => $value) {
          $keyOrKeyArray[$key] = new Key($value, $algorithm);
        }
        if (count($keyOrKeyArray) === 0) {
          throw new InvalidArgumentException('Can not decode token. $publicKey can not be empty array');
        }
      }

      $data = (array) JWT::decode($tokenStr->getValueAsString(), $keyOrKeyArray, $headers);
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