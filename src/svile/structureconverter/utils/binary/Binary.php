<?php

/*
 *                _   _
 *  ___  __   __ (_) | |   ___
 * / __| \ \ / / | | | |  / _ \
 * \__ \  \ / /  | | | | |  __/
 * |___/   \_/   |_| |_|  \___|
 *
 * @Author: svile
 * @Kik: _svile_
 * @Telegram_Group: https://telegram.me/svile
 * @E-mail: thesville@gmail.com
 * @Github: https://github.com/svilex
 *
 */

namespace svile\cuboidconverter\utils\binary;

if(!defined('ENDIANNESS'))
    define('ENDIANNESS', (pack('d', 1) === "\77\360\0\0\0\0\0\0" ? Binary::BIG_ENDIAN : Binary::LITTLE_ENDIAN));

abstract class Binary
{
    const BIG_ENDIAN = 0x00;
    const LITTLE_ENDIAN = 0x01;


    /**
     * Reads a 3-byte big-endian number
     *
     * @param $str
     *
     * @return mixed
     */
    public static function readTriad($str)
    {
        return unpack('N', "\x00" . $str)[1];
    }


    /**
     * Writes a 3-byte big-endian number
     *
     * @param $value
     *
     * @return string
     */
    public static function writeTriad($value)
    {
        return substr(pack('N', $value), 1);
    }


    /**
     * Reads a 3-byte little-endian number
     *
     * @param $str
     *
     * @return mixed
     */
    public static function readLTriad($str)
    {
        return unpack('V', $str . "\x00")[1];
    }


    /**
     * Writes a 3-byte little-endian number
     *
     * @param $value
     *
     * @return string
     */
    public static function writeLTriad($value)
    {
        return substr(pack('V', $value), 0, -1);
    }


    /**
     * Reads a byte boolean
     *
     * @param $b
     *
     * @return bool
     */
    public static function readBool($b)
    {
        return self::readByte($b, false) === 0 ? false : true;
    }


    /**
     * Writes a byte boolean
     *
     * @param $b
     *
     * @return bool|string
     */
    public static function writeBool($b)
    {
        return self::writeByte($b === true ? 1 : 0);
    }


    /**
     * Reads an unsigned/signed byte
     *
     * @param string $c
     * @param bool $signed
     *
     * @return int
     */
    public static function readByte($c, $signed = true)
    {
        $b = ord($c{0});

        if ($signed) {
            if (PHP_INT_SIZE === 8) {
                return $b << 56 >> 56;
            } else {
                return $b << 24 >> 24;
            }
        } else {
            return $b;
        }
    }


    /**
     * Writes an unsigned/signed byte
     *
     * @param $c
     *
     * @return string
     */
    public static function writeByte($c)
    {
        return chr($c);
    }


    /**
     * Reads a 16-bit unsigned big-endian number
     *
     * @param $str
     *
     * @return int
     */
    public static function readShort($str)
    {
        return unpack('n', $str)[1];
    }


    /**
     * Reads a 16-bit signed big-endian number
     *
     * @param $str
     *
     * @return int
     */
    public static function readSignedShort($str)
    {
        if (PHP_INT_SIZE === 8) {
            return unpack('n', $str)[1] << 48 >> 48;
        } else {
            return unpack('n', $str)[1] << 16 >> 16;
        }
    }


    /**
     * Writes a 16-bit signed/unsigned big-endian number
     *
     * @param $value
     *
     * @return string
     */
    public static function writeShort($value)
    {
        return pack('n', $value);
    }


    /**
     * Reads a 16-bit signed/unsigned little-endian number
     *
     * @param      $str
     * @param bool $signed
     *
     * @return int
     */
    public static function readLShort($str, $signed = true)
    {
        $unpacked = unpack('v', $str)[1];

        if ($signed) {
            if (PHP_INT_SIZE === 8) {
                return $unpacked << 48 >> 48;
            } else {
                return $unpacked << 16 >> 16;
            }
        } else {
            return $unpacked;
        }
    }


    /**
     * Writes a 16-bit signed/unsigned little-endian number
     *
     * @param $value
     *
     * @return string
     */
    public static function writeLShort($value)
    {
        return pack('v', $value);
    }


    public static function readInt($str)
    {
        if (PHP_INT_SIZE === 8) {
            return unpack('N', $str)[1] << 32 >> 32;
        } else {
            return unpack('N', $str)[1];
        }
    }


    public static function writeInt($value)
    {
        return pack('N', $value);
    }


    public static function readLInt($str)
    {
        if (PHP_INT_SIZE === 8) {
            return unpack('V', $str)[1] << 32 >> 32;
        } else {
            return unpack('V', $str)[1];
        }
    }


    public static function writeLInt($value)
    {
        return pack('V', $value);
    }


    public static function readFloat($str)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? unpack('f', $str)[1] : unpack('f', strrev($str))[1];
    }


    public static function writeFloat($value)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? pack('f', $value) : strrev(pack('f', $value));
    }


    public static function readLFloat($str)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? unpack('f', strrev($str))[1] : unpack('f', $str)[1];
    }


    public static function writeLFloat($value)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? strrev(pack('f', $value)) : pack('f', $value);
    }


    public static function printFloat($value)
    {
        return preg_replace("/(\\.\\d+?)0+$/", '$1', sprintf('%F', $value));
    }


    public static function readDouble($str)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? unpack('d', $str)[1] : unpack('d', strrev($str))[1];
    }


    public static function writeDouble($value)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? pack('d', $value) : strrev(pack('d', $value));
    }


    public static function readLDouble($str)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? unpack('d', strrev($str))[1] : unpack('d', $str)[1];
    }


    public static function writeLDouble($value)
    {
        return ENDIANNESS === self::BIG_ENDIAN ? strrev(pack('d', $value)) : pack('d', $value);
    }


    public static function readLong($x)
    {
        if (PHP_INT_SIZE === 8) {
            $int = unpack('N*', $x);
            return ($int[1] << 32) | $int[2];
        } else {
            $value = '0';
            for ($i = 0; $i < 8; $i += 2) {
                $value = bcmul($value, '65536', 0);
                $value = bcadd($value, self::readShort(substr($x, $i, 2)), 0);
            }

            if (bccomp($value, '9223372036854775807') == 1) {
                $value = bcadd($value, '-18446744073709551616');
            }

            return $value;
        }
    }


    public static function writeLong($value)
    {
        if (PHP_INT_SIZE === 8) {
            return pack('NN', $value >> 32, $value & 0xFFFFFFFF);
        } else {
            $x = '';

            if (bccomp($value, '0') == -1) {
                $value = bcadd($value, '18446744073709551616');
            }

            $x .= self::writeShort(bcmod(bcdiv($value, '281474976710656'), '65536'));
            $x .= self::writeShort(bcmod(bcdiv($value, '4294967296'), '65536'));
            $x .= self::writeShort(bcmod(bcdiv($value, '65536'), '65536'));
            $x .= self::writeShort(bcmod($value, '65536'));

            return $x;
        }
    }


    public static function readLLong($str)
    {
        return self::readLong(strrev($str));
    }


    public static function writeLLong($value)
    {
        return strrev(self::writeLong($value));
    }
}