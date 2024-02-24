<?php

namespace TigerCore\Utils;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ValueObject\VO_Base64Hash;
use TigerCore\ValueObject\VOM_CipherMethod;
use TigerCore\ValueObject\VO_PasswordPlainText;

class Crypt {

  /** Generate Initialization vector based on $asciiStringSeed
   * @param string $asciiStringSeed
   * @param int $length
   * @return string
   */
  private static function generateIV(string $asciiStringSeed, int $length = 16): string {
    $chars = str_split($asciiStringSeed);
    $result = '';
    foreach ($chars as $oneChar) {
      $result = $result.(string)ord($oneChar);
    }

    while (strlen($result) < $length) {
      $result = $result + $result;
    }

    return substr($result,0, $length);
  }


  /**
   * @param string $data
   * @param VO_PasswordPlainText $password
   * @return VO_Base64Hash Returns encoded string
   * @throws \Exception
   */
  public static function encode(string $data, VO_PasswordPlainText $password):VO_Base64Hash {
    // Store a string into the variable which
    // need to be Encrypted
    $simple_string = $data;

    // Store the cipher method
    $ciphering = new VOM_CipherMethod(VOM_CipherMethod::CIPHER_METHOD_AES_128_CTR);

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering->getValueAsString());

    if ($iv_length === false) {
      throw new \Exception('Cipher method ' . $ciphering->getValueAsString() . ' not supported.');
    }

    $options = OPENSSL_RAW_DATA;

    // Non-NULL Initialization Vector for encryption
    $encryption_iv = random_bytes($iv_length);

    // Store the encryption key
    $encryption_key = $password->getValueAsString();

    // Use openssl_encrypt() function to encrypt the data
    $encryption = openssl_encrypt($simple_string, $ciphering->getValueAsString(),
      $encryption_key, $options, $encryption_iv);

    if ($encryption === false) {
      throw new \Exception('openssl_encrypt failed. Cipher method: ' .$ciphering);
    }

    // Return the encrypted string
    return new VO_Base64Hash(base64_encode(pack('C',$ciphering->getValueAsInt()).$encryption_iv.$encryption));
  }

  /**
   * @param VO_Base64Hash $encryptedData
   * @param VO_PasswordPlainText $password
   * @return string
   * @throws InvalidArgumentException
   * @throws \Exception
   */
  public static function decode(VO_Base64Hash $encryptedData, VO_PasswordPlainText $password):string {
    $encryptedDataRaw = base64_decode($encryptedData->getValueAsString());

    if ($encryptedDataRaw === false) {
      throw new \Exception('Encrypted data is not base64 string');
    }

    if (strlen($encryptedDataRaw) < 2) {
      throw new \Exception('Invalid $encryptedData data. Too short');
    }

    // First byte is Cipher method id from VO_CipherMethod
    $cipherMethod = current(unpack('C',substr($encryptedDataRaw,0,1)));

    try {
      $cipherMethod = new VOM_CipherMethod($cipherMethod);
    } catch (\Exception) {
      throw new \Exception('Invalid cipher method: '.$cipherMethod);
    }

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($cipherMethod->getValueAsString());
    if ($iv_length === false) {
      throw new \Exception('Cipher method ' . $cipherMethod->getValueAsString() . ' not supported.');
    }

    $options = OPENSSL_RAW_DATA;

    // Non-NULL Initialization Vector for encryption
    $decryption_iv = substr($encryptedDataRaw,1,$iv_length);

    if (strlen($decryption_iv) < $iv_length) {
      throw new \Exception('Not enought bytes for IVector. Want: '.$iv_length.'bytes. Got: '.strlen($decryption_iv).'bytes');
    }

    $encryptedDataRaw = substr($encryptedDataRaw, 1 + $iv_length);

    if (strlen($encryptedDataRaw) === 0) {
      throw new \Exception('No data left to decrypt.');
    }

    // Store the encryption key
    $decryption_key = $password->getValueAsString();


    // Use openssl_decrypt() function to decrypt the data
    $decryption = openssl_decrypt(
      $encryptedDataRaw,
      $cipherMethod->getValueAsString(),
      $decryption_key,
      $options,
      $decryption_iv
    );

    if ($decryption === false) {
      throw new InvalidArgumentException();
    }

    return $decryption;
  }

}