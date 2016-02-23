<?php
namespace App\Lib;

class Functions
{
    public static function int2str($num, $lastGender = 0)
    {
        $lastGender = intval(boolval($lastGender));
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ];

        $a20     = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
        $tens    = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];

        $units = [
            ['миллиард', 'милиарда', 'миллиардов', 0],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
        ];

        $number = sprintf("%012d", intval($num));

        $out = [];
        if (intval($number) > 0) {
            foreach (str_split($number, 3) as $key => $value) { // by 3 symbols
                if (intval($value) === 0) {
                    continue;
                }

                $gender = isset($units[$key]) ? $units[$key][3] : $lastGender;

                list($i1, $i2, $i3) = array_map('intval', str_split($value, 1));

                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1) {
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                } else {
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                }

                if (isset($units[$key])) {
                    $out[] = self::morph($value, $units[$key][0], $units[$key][1], $units[$key][2]);
                }
            }
        } else {
            $out[] = $nul;
        };

        return trim(preg_replace('/ {2,}/', ' ', implode(' ', $out)));
    }

    public static function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) {
            return $f5;
        }

        $n = $n % 10;
        if ($n > 1 && $n < 5) {
            return $f2;
        }

        if ($n == 1) {
            return $f1;
        }
        return $f5;
    }
}
