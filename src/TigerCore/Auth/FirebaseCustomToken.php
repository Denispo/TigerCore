<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_FirebaseCustomTokenDuration;
use TigerCore\ValueObject\VO_TokenPlainStr;
use Firebase\JWT\JWT;

/**
 * https://firebase.google.com/docs/auth/admin/create-custom-tokens
 * We do not need to decode FirebaseCustomToken. This token is onlz generated and then consumed by Firebase SDK on the client.
 */
class FirebaseCustomToken{

  /**
   * @param array{client_email:string,private_key:string} $serviceAccountJsonData Data from encoded service account adminsdk JSON
   * @param int|string $userId
   * @param ICanGetTokenClaims|null $claims
   * @param VO_FirebaseCustomTokenDuration|null $duration Default duration is 3600 seconds
   * @return VO_TokenPlainStr
   * @throws InvalidArgumentException
   * @throws InvalidTokenException
   */
  public static function generateToken(
    array $serviceAccountJsonData,
    int|string                       $userId,
    ICanGetTokenClaims|null          $claims = null,
    VO_FirebaseCustomTokenDuration   $duration = null):VO_TokenPlainStr
  {
    $userId = trim((string)$userId);
    if ($userId === '' || $userId == 0) {
      throw new InvalidArgumentException('Can not generate FB Custom token for empty user Id');
    }

    if ($duration === null) {
      $duration = new VO_FirebaseCustomTokenDuration(3600);
    }

    if (!($serviceAccountJsonData['private_key'] && $serviceAccountJsonData['client_email'])) {
      throw new InvalidArgumentException('FB service account is missing privateKey and/or clientEmail');
    }

    $nowSeconds = time()-30; // server time is allowed to be 30 seconds off

    // https://firebase.google.com/docs/auth/admin/create-custom-tokens
    $payload = array(
      "iss" => $serviceAccountJsonData['client_email'],
      "sub" => $serviceAccountJsonData['client_email'],
      "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
      "iat" => $nowSeconds,
      "exp" => $nowSeconds+($duration->getValueAsInt()),  // Maximum expiration time is one hour (3600 seconds)
      "uid" => $userId,
      "claims" => $claims?->getClaims()?? [],
    );
    try {
      $result = JWT::encode($payload, $serviceAccountJsonData['private_key'],'RS256');
    } catch (\Throwable $e) {
      throw new InvalidTokenException( new TokenError(TokenError::ERR_NA), 'Can not generate FirebaseCustomAuthToken');
    }
    return new VO_TokenPlainStr($result);
  }



}