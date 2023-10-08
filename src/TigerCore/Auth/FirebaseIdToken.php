<?php

namespace TigerCore\Auth;

use Firebase\JWT\JWT;
use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_TokenPlainStr;
use TigerCore\ValueObject\VO_TokenPublicKey;

/*
 * https://firebase.google.com/docs/auth/admin/verify-id-tokens
 * Firebase ID token is generated on the client via firebase server. Therefore, it does not have to be generated there
 */
class FirebaseIdToken{

  /**
   * @param VO_TokenPublicKey $publicKey
   * @param VO_TokenPlainStr $tokenStr
   * @param array $publicKeyIds data from https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com
   * @return BaseTokenPayload
   * @throws InvalidTokenException
   */
  public static function decodeToken(VO_TokenPublicKey $publicKey, VO_TokenPlainStr $tokenStr, array $publicKeyIds): BaseTokenPayload {
    $decodedToken = BaseJwtToken::decodeToken($publicKey, $tokenStr, 'RS256');

    // https://firebase.google.com/docs/auth/admin/verify-id-tokens#verify_id_tokens_using_a_third-party_jwt_library
    $authTime = $decodedToken->getClaims()['auth_']?? -1;
    if ($authTime === -1) {
      throw new InvalidTokenException(new TokenError(TokenError::ERR_INVALID_AUTHENTICATION_TIME),'Missin authentication time claim "_auth"');
    }
    if ($authTime >= time()) {
      throw new InvalidTokenException(new TokenError(TokenError::ERR_INVALID_AUTHENTICATION_TIME),'Authentication time claim "_auth" can not be in the future');
    }

    $kid =  $decodedToken->getClaims()['kid']?? '';
    if ($kid === '') {
      throw new InvalidTokenException(new TokenError(TokenError::ERR_INVALID_KEYID),'Missing Key ID claim "kid"');
    }

    if (!array_key_exists($kid, $publicKeyIds)) {
      throw new InvalidTokenException(new TokenError(TokenError::ERR_INVALID_KEYID),'Invalid Key ID claim "kid"');
    }

    JWT::ve

    return $decodedToken;
  }

}