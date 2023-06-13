<?php

namespace TigerCore\Utils;

use TigerCore\Exceptions\ExpiredException;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\ValueObject\VO_Base64Hash;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_PasswordPlainText;

class CryptedHash {

  /**
   * @param string|int|ICanGetValueAsString|ICanGetValueAsInit $value
   * @param VO_PasswordPlainText $passphrase
   * @return VO_Base64Hash
   * @throws InvalidArgumentException
   * @throws \Exception
   */
  public static function generateCryptedHash(string|int|ICanGetValueAsString|ICanGetValueAsInit $value, VO_PasswordPlainText $passphrase):VO_Base64Hash {
    if ($value instanceof ICanGetValueAsString){
      $value = $value->getValueAsString();
    } elseif ($value instanceof ICanGetValueAsInit){
      $value = $value->getValueAsInt();
    }

    // Time is rouded to 5 minute granularity (to save some bytes)
    $timestampPacked = pack('V',round(ceil(time() / 60*5))); // 32 bit unsigned long. Little endian

    // We can remove first (because Little endian) byte, because it is always 0 (because time() / (60*5))
    // Little endian (DCBA): 16 bit 0xAABB is stored as 32 bit 0x0000BBAA
    $timestampPacked = substr($timestampPacked, 1);
    // now $timestampPacked is 3 bytes long

    if (is_int($value)) {
      $value = pack('l', $value); // 32 bit signed integer, machine dependent byte oreder (means little or big endian)
    } else {
      if (strlen($value) === 4) {
        // Length of $userIdPacked can not be 4 bytes. 4 bytes length is "reserved" for integer version of UserId. Because pack('l', $userId); always returns 4 bytes length string.
        // If length of $userIdPacked is accidentaly 4 bytes, we have to add one additional byte to recognize that $userIdPacked is string.
        $value = $value . "\x00";
      }
    }
    return Crypt::encode($timestampPacked.$value, $passphrase);
  }

  /**
   * @param VO_Base64Hash $hash
   * @param VO_PasswordPlainText $passphrase
   * @param VO_Duration|null $hashDuration
   * @return string|int
   * @throws ExpiredException
   * @throws InvalidArgumentException
   */
  public static function getValueFromHash(VO_Base64Hash $hash, VO_PasswordPlainText $passphrase, VO_Duration $hashDuration = null):string|int {
    $binary = Crypt::decode($hash, $passphrase);
    if (strlen($binary) < 4) {
      // 3 bytes timestamp + 1 byte (at least) UserId
      throw new InvalidArgumentException();
    }
    //                                          \x00 = we have to add one zero byte, which was removed when timestamp was crypted
    $timestamp = current(unpack('V',"\x00".substr($binary,0,3))) * (60*5);

    if ($hashDuration && (($timestamp + $hashDuration->getValueAsInt() < time()) || ($timestamp - (5*60) > time()))) {
      // $timestamp - (5*60) can not be bigger than time()
      throw new ExpiredException();
    }

    $value = substr($binary, 3);

    if (strlen($value) === 4) {
      // $value is number,
      $value = current(unpack('l', $value)); // 32 bit signed integer, machine dependent byte order (little or big endian)
    } else {
      // $value is a string
      $value = rtrim($value,"\x00");
    }

    return $value;

  }

}