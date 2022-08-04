<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_Timestamp;
use TigerCore\ValueObject\VO_TokenPlainStr;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

abstract class BaseJwtToken{

  protected abstract function onGetTokenSettings():JwtTokenSettings;


  /**
   * @param VO_TokenPlainStr $tokenStr
   * @return BaseTokenClaims
   * @throws InvalidTokenException
   */
  protected function doDecodeToken(VO_TokenPlainStr $tokenStr): BaseTokenClaims {
    try {
      $data = (array) JWT::decode($tokenStr->getValue(), new Key($this->onGetTokenSettings()->getPublicKey()->getValue(), 'RS256'));
    } catch (\InvalidArgumentException|\DomainException|\UnexpectedValueException|SignatureInvalidException|BeforeValidException|ExpiredException $e) {
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

    return new BaseTokenClaims($data);
  }

  /**
   * @param BaseTokenClaims $claims
   * @param VO_Duration $duration
   * @return VO_TokenPlainStr
   * @throws \DomainException Unsupported algorithm or bad key was specified
   */
  protected function doEncodeToken(BaseTokenClaims $claims, VO_Duration $duration):VO_TokenPlainStr {

    $privateKey = $this->onGetTokenSettings()->getPrivateKey();

    if ($privateKey->isEmpty()) {
      return new VO_TokenPlainStr('');
    }

    $alg = 'RS256';

    $expirationDate = (new VO_Timestamp(time()))->addDuration($duration);

    $tokenStr = JWT::encode(
      array_merge(
        $claims->getClaims(),
        [
          'iat' => time(),
          'exp' => $expirationDate->getValue(),
        ]
      ),
      $privateKey->getValue(),
      $alg
    );

    return new VO_TokenPlainStr($tokenStr);

  }

}