<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_FullPathFileName;
use TigerCore\ValueObject\VO_TokenPlainStr;
use Firebase\JWT\JWT;
use TigerCore\ValueObject\VO_TokenPublicKey;

class FirebaseCustomToken{

  private const FIREBASE_TOKEN_ALGORITHM = 'RS256';

  /**
   * @param VO_TokenPublicKey $publicKey
   * @param VO_TokenPlainStr $tokenStr
   * @return BaseTokenClaims
   * @throws InvalidTokenException
   */
  public static function decodeToken(VO_TokenPublicKey $publicKey, VO_TokenPlainStr $tokenStr): BaseTokenClaims {
    return BaseJwtToken::decodeToken($publicKey, $tokenStr, self::FIREBASE_TOKEN_ALGORITHM);
  }

  /**
   * @param VO_FullPathFileName|string|array $serviceAccountJson Path to file or encoded JSON string or decoded JSON as associative array
   * @param int|string $userId
   * @param ICanGetTokenClaims|null $claims
   * @param int $durationInSeconds Maximum duration is 3600 seconds
   * @return VO_TokenPlainStr
   * @throws InvalidArgumentException
   * @throws InvalidTokenException
   */
  private static function encodeToken(VO_FullPathFileName|string|array $serviceAccountJson, int|string $userId, ICanGetTokenClaims|null $claims = null, int $durationInSeconds = 60*60):VO_TokenPlainStr
  {
    $userId = trim((string)$userId);
    if ($userId === '' || $userId == 0) {
      throw new InvalidArgumentException('Can not generate FB Custom token. Empty user Id');
    }

    if ($durationInSeconds < 1 || $durationInSeconds > 60 * 60) {
      throw new InvalidArgumentException('FB custom token invalid duration: '.$durationInSeconds.' seconds');
    }

    $json = '';

    if ($serviceAccountJson instanceof VO_FullPathFileName) {
      $json = @file_get_contents($serviceAccountJson->getValueAsString());
      if ($json === false) {
        throw new InvalidArgumentException('FB Service account: Invalid file name');
      }
    } elseif (is_string($serviceAccountJson)) {
      $json = trim($serviceAccountJson);
      if ($json === '') {
        throw new InvalidArgumentException('FB Service account can not be empty string');
      }
    }

    // if $json is empty string it means $json is already decoded array
    if ($json !== '') {
      $json = json_decode($json, true);
      if ($json === null) {
        throw new InvalidArgumentException('FB Service account Json can not be decoded');
      }
    }

    if (!($json['private_key'] && $json['client_email'])) {
      throw new InvalidArgumentException('FB service account is missing privateKey and/or clientEmail');
    }

    $nowSeconds = time()-30; // server time is allowed to be 30 seconds off

    // https://firebase.google.com/docs/auth/admin/create-custom-tokens
    $payload = array(
      "iss" => $json['client_email'],
      "sub" => $json['client_email'],
      "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
      "iat" => $nowSeconds,
      "exp" => $nowSeconds+($durationInSeconds),  // Maximum expiration time is one hour (3600 seconds)
      "uid" => $userId,
      "claims" => $claims?->getClaims()?? [],
    );
    try {
      $result = JWT::encode($payload, $json['private_key'],self::FIREBASE_TOKEN_ALGORITHM);
    } catch (\Throwable $e) {
      throw new InvalidTokenException( new TokenError(TokenError::ERR_NA), 'Can not generate FirebaseCustomAuthToken');
    }
    return new VO_TokenPlainStr($result);
  }



}