<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_Timestamp;
use TigerCore\ValueObject\VO_TokenPlainStr;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Nette\Utils\Arrays;

abstract class BaseJwtToken implements ICanGetTokenStrForUser, ICanParseTokenStr{

  protected abstract function onGetClaims(): array;

  protected abstract function onGetTokenSettings():JwtTokenSettings;


  /**
   * @param VO_TokenPlainStr $tokenStr
   * @return BaseUserTokenData
   * @throws InvalidTokenException
   */
  public function parseToken(VO_TokenPlainStr $tokenStr): BaseUserTokenData {
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

    $userId = Arrays::get($data, 'uid', '');
    return new BaseUserTokenData(new VO_BaseId($userId), Arrays::get($data, 'claims', []));
  }

  public function getTokenStr(VO_BaseId $userId):VO_TokenPlainStr {
    if (!$userId->isValid()) {
      return new VO_TokenPlainStr('');
    }

    $privateKey = $this->onGetTokenSettings()->getPrivateKey();

    if ($privateKey->isEmpty()) {
      return new VO_TokenPlainStr('');
    }

    $claims = $this->onGetClaims();

    $expirationDate = (new VO_Timestamp(time()))->addDuration($this->onGetTokenSettings()->getDuration());

    $tokenStr = JWT::encode([
      'uid' => $userId->getValue(),
      'iat' => time(),
      'exp' => $expirationDate->getValue(),
      'claims' => $claims
    ], $privateKey->getValue(), 'RS256');

    return new VO_TokenPlainStr($tokenStr);

  }

}