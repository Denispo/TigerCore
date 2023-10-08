<?php

namespace TigerCore\Auth;

use TigerCore\Constants\TokenError;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_TokenPlainStr;
use TigerCore\ValueObject\VO_TokenPublicKey;

/*
 * https://firebase.google.com/docs/auth/admin/verify-id-tokens
 * Firebase ID token is generated on the client via firebase server. Therefore, it does not have to be generated there
 */
class FirebaseIdToken{

  /**
   * @param VO_TokenPublicKey|array<string, string> $publicKey
   *      array<KeyId,PublicKey> data from "client_x509_cert_url" from firebase-adminsdk JSON (i.e. https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com)
   *      If VO_TokenPublicKey, "key" claims from JWT header will be ignored and VO_TokenPublicKey will be used no matter if it is valid public key for current "key" Id.
   *      If VO_TokenPublicKey is used, caller must quarantee that VO_TokenPublicKey match Private key IdToken was signed with. Otherwise, decode will fail.
   *      If data from endpoint "client_x509_cert_url" is used,  decodeToken() will search for corresponding public key based on "key" claims from JWT header.
   *      Using "client_x509_cert_url" strategy is neccesary if third party IdTokens are enabled (facebook. etc.) because in this scenario we do not know KeyId nor corresponding PublicKey
   *
   * @param VO_TokenPlainStr $tokenStr
   * @return BaseTokenPayload
   * @throws InvalidTokenException
   * @throws InvalidArgumentException
   */
  public static function decodeToken(VO_TokenPublicKey|array $publicKey, VO_TokenPlainStr $tokenStr): BaseTokenPayload {
    $decodedToken = BaseJwtToken::decodeToken($tokenStr, $publicKey, 'RS256');

    // https://firebase.google.com/docs/auth/admin/verify-id-tokens#verify_id_tokens_using_a_third-party_jwt_library
    $authTime = $decodedToken->getClaims()['auth_']?? -1;
    if ($authTime === -1) {
      throw new InvalidTokenException(new TokenError(TokenError::ERR_INVALID_AUTHENTICATION_TIME),'Missin authentication time claim "_auth"');
    }
    if ($authTime >= time()) {
      throw new InvalidTokenException(new TokenError(TokenError::ERR_INVALID_AUTHENTICATION_TIME),'Authentication time claim "_auth" can not be in the future');
    }

    return $decodedToken;
  }

}