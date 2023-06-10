<?php

namespace TigerCore\Utils;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ValueObject\VO_CipherMethod;

class Crypt {

  //private const AVAILABLE_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

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
   * @param string $password
   * @return string Returns encoded string
   * @throws \Exception
   */
  public static function encode(string $data, string $password):string {
    // Store a string into the variable which
    // need to be Encrypted
    $simple_string = $data;

    // Store the cipher method
    $ciphering = new VO_CipherMethod(VO_CipherMethod::CIPHER_METHOD_AES_128_CTR);

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering->getValueAsString());

    if ($iv_length === false) {
      throw new \Exception('Cipher method ' . $ciphering->getValueAsString() . ' not supported.');
    }

    $options = OPENSSL_RAW_DATA;

    //$len = strlen(self::AVAILABLE_CHARS) - 1;

    //$asciiString = self::AVAILABLE_CHARS[random_int(0,$len)].self::AVAILABLE_CHARS[random_int(0,$len)].self::AVAILABLE_CHARS[random_int(0,$len)].self::AVAILABLE_CHARS[random_int(0,$len)];

    // Non-NULL Initialization Vector for encryption
    $encryption_iv = random_bytes($iv_length);

    // Store the encryption key
    $encryption_key = $password;

    // Use openssl_encrypt() function to encrypt the data
    $encryption = openssl_encrypt($simple_string, $ciphering->getValueAsString(),
      $encryption_key, $options, $encryption_iv);

    if ($encryption === false) {
      throw new \Exception('openssl_encrypt failed. Cipher method: ' .$ciphering);
    }

    // Return the encrypted string

    return base64_encode(pack('C',$ciphering->getValueAsInt()).$encryption_iv.$encryption);
  }

  /**
   * @param string $encryptedData
   * @param string $password
   * @return string
   * @throws InvalidArgumentException
   * @throws \Exception
   */
  public static function decode(string $encryptedData, string $password):string {
    if ($password == '' || $encryptedData == '') {
      return '';
    }

    $encryptedData = base64_decode($encryptedData);

    if ($encryptedData === false) {
      throw new \Exception('Encrypted data is not base64 string');
    }

    if (strlen($encryptedData) < 2) {
      throw new \Exception('Invalid $encryptedData data. Too short');
    }

    // First byte is Cipher method id from VO_CipherMethod
    $cipherMethod = current(unpack('C',substr($encryptedData,0,1)));

    try {
      $cipherMethod = new VO_CipherMethod($cipherMethod);
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
    $decryption_iv = substr($encryptedData,1,$iv_length);

    if (strlen($decryption_iv) < $iv_length) {
      throw new \Exception('Not enought bytes for IVector. Want: '.$iv_length.'bytes. Got: '.strlen($decryption_iv).'bytes');
    }

    $encryptedData = substr($encryptedData, 1 + $iv_length);

    if (strlen($encryptedData) === 0) {
      throw new \Exception('No data left to decrypt.');
    }

    // Store the encryption key
    $decryption_key = $password;


    // Use openssl_decrypt() function to decrypt the data
    $decryption = openssl_decrypt($encryptedData, $cipherMethod->getValueAsString(),
      $decryption_key, $options, $decryption_iv);

    if ($decryption === false) {
      throw new InvalidArgumentException();
    }

    return $decryption;
  }

}