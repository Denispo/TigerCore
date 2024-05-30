<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\JwtTokenUtils;
use TigerCore\ValueObject\VO_FirebaseCustomTokenDuration;
use TigerCore\ValueObject\VO_TokenPlainStr;
use TigerCore\ValueObject\VO_TokenPrivateKey;

/**
 * https://firebase.google.com/docs/auth/admin/create-custom-tokens
 * We do not need to decode FirebaseCustomToken. This token is only generated and then consumed by Firebase SDK on the client.
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
    VO_FirebaseCustomTokenDuration   $duration = null
  ):VO_TokenPlainStr
  {
    $userId = trim((string)$userId);
    if ($userId === '' || $userId == 0) {
      throw new InvalidArgumentException('Can not generate FB Custom token for empty user Id');
    }

    if ($duration === null) {
      $duration = new VO_FirebaseCustomTokenDuration(3600);
    }


    try {
      $privateKey = new VO_TokenPrivateKey($serviceAccountJsonData['private_key']);
    } catch (InvalidArgumentException $e) {
      throw new InvalidArgumentException('FB service account is missing private_key');
    }

    if (!$serviceAccountJsonData['client_email']) {
      throw new InvalidArgumentException('FB service account is missing client_email');
    }

    // https://firebase.google.com/docs/auth/admin/create-custom-tokens
    $payload = array(
      "iss" => $serviceAccountJsonData['client_email'],
      "sub" => $serviceAccountJsonData['client_email'],
      "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
      "uid" => $userId,
    );
    if ($claims) {
      // https://stackoverflow.com/a/45536473
        // content from $payload["claims"] will be rendered in the IdToken root, so we have to add claims into claims. See FirebaseIdTokenClaims.php
      $payload["claims"] = ['claims'=>$claims->getClaims()];
    }
    try {
      $result = JwtTokenUtils::encodeToken($privateKey, $duration, $payload, 'RS256');
    } catch (\Throwable $e) {
      throw new InvalidTokenException( new TokenError(TokenError::ERR_NA), 'Can not generate FirebaseCustomAuthToken');
    }
    return $result;
  }



}