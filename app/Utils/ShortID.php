<?php

namespace App\Utils;

/**
 * Short id generator. Url-friendly. Non-predictable
 *
 * Solution taken from here:
 * http://stackoverflow.com/a/13733588/1056679
 */
class ShortID
{
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';

    /**
     * Generate Short ID by specified length
     *
     * @param void
     *
     * @return string $shortId
     */
    public static function generate($length)
    {
        $token = '';
        $alphabetLength = strlen(self::ALPHABET);

        for ($i = 0; $i < $length; $i++) {
            $randomKey = self::getRandomInteger(0, $alphabetLength);
            $token .= substr(self::ALPHABET, $randomKey - 1, 1);
        }

        return $token;
    }

    /**
     * Get random integer between min and max
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public static function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);

        return ($min + $rnd);
    }
}
