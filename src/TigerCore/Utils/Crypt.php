<?php

namespace TigerCore\Utils;

class Crypt {

  private const AVAILABLE_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

  private static function generateIV(string $asciiString, int $length = 16): string {
    $chars = str_split($asciiString);
    $result = '';
    foreach ($chars as $oneChar) {
      $result = $result.(string)ord($oneChar);
    }

    while (strlen($result) < $length) {
      $result = $result + $result;
    }

    return substr($result,0, $length);
  }


  public static function encode(string $data, string $password):string {
    // Store a string into the variable which
    // need to be Encrypted
    $simple_string = $data;

    // Store the cipher method
    $ciphering = "AES-128-CTR";

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    $len = strlen(self::AVAILABLE_CHARS) - 1;

    $asciiString =self::AVAILABLE_CHARS[random_int(0,$len)].self::AVAILABLE_CHARS[random_int(0,$len)].self::AVAILABLE_CHARS[random_int(0,$len)].self::AVAILABLE_CHARS[random_int(0,$len)];

    // Non-NULL Initialization Vector for encryption
    $encryption_iv = self::generateIV($asciiString,$iv_length);

    // Store the encryption key
    $encryption_key = $password;

    // Use openssl_encrypt() function to encrypt the data
    $encryption = openssl_encrypt($simple_string, $ciphering,
      $encryption_key, $options, $encryption_iv);

    // Display the encrypted string
    return $asciiString.$encryption;

  }

  public static function decode(string $encryptedData, string $password):string {

    // Store the cipher method
    $ciphering = "AES-128-CTR";

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    // Non-NULL Initialization Vector for encryption
    $decryption_iv = self::generateIV(substr($encryptedData,0,4));

    // Store the encryption key
    $decryption_key = $password;


    // Use openssl_decrypt() function to decrypt the data
    $decryption = openssl_decrypt (substr($encryptedData,4), $ciphering,
      $decryption_key, $options, $decryption_iv);

    return $decryption;
  }

}