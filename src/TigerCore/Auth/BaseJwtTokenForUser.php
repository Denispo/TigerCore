<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_Timestamp;
use TigerCore\ValueObject\VO_TokenPlainStr;
use TigerCore\ValueObject\VO_TokenPrivateKey;
use TigerCore\ValueObject\VO_TokenPublicKey;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Nette\Utils\Arrays;

abstract class BaseJwtTokenForUser implements ICanGetTokenStrForUser, ICanParseTokenStr{

  public function __construct(
    private VO_TokenPrivateKey $privateKey,
    private VO_TokenPublicKey $publicKey
  ) {

  }

  protected abstract function onGetClaims(): array;

  protected abstract function onGetTokenExpirationDate():VO_Timestamp;


  /**
   * @param VO_TokenPlainStr $tokenStr
   * @return BaseDecodedTokenData
   * @throws InvalidTokenException
   */
  public function parseToken(VO_TokenPlainStr $tokenStr): BaseDecodedTokenData {
    try {
      $data = JWT::decode($tokenStr->getValue(), new Key($this->publicKey->getValue(), 'RS256'));
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
    return new BaseDecodedTokenData(new VO_BaseId($userId), Arrays::get((array)$data, 'claims', []));
  }

  public function getTokenStr(VO_BaseId $userId):VO_TokenPlainStr {
    if (!$userId->isValid()) {
      return new VO_TokenPlainStr('');
    }

    $claims = $this->onGetClaims();

    $expirationDate = $this->onGetTokenExpirationDate();

    $tokenStr = JWT::encode([
      'uid' => $userId->getValue(),
      'iat' => time(),
      'exp' => $expirationDate->getValue(),
      'claims' => $claims
    ], $this->privateKey->getValue(), 'RS256');

    return new VO_TokenPlainStr($tokenStr);

  }

}