<?php

namespace App\Helpers;

class NumberToWords
{
    private static array $ones = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen'
    ];

    private static array $tens = [
        '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
    ];

    public static function convert(float $amount): string
    {
        $ringgit = floor($amount);
        $sen = round(($amount - $ringgit) * 100);

        $words = self::convertNumber((int) $ringgit);

        if ($sen > 0) {
            $words .= ' and ' . self::convertNumber((int) $sen) . ' Sen';
        }

        return $words;
    }

    private static function convertNumber(int $num): string
    {
        if ($num === 0) return 'Zero';

        $parts = [];

        if ($num >= 1000000) {
            $parts[] = self::convertNumber((int) ($num / 1000000)) . ' Million';
            $num %= 1000000;
        }
        if ($num >= 1000) {
            $parts[] = self::convertNumber((int) ($num / 1000)) . ' Thousand';
            $num %= 1000;
        }
        if ($num >= 100) {
            $parts[] = self::$ones[(int) ($num / 100)] . ' Hundred';
            $num %= 100;
        }
        if ($num >= 20) {
            $tens = self::$tens[(int) ($num / 10)];
            $num %= 10;
            $parts[] = $num > 0 ? "{$tens}-" . self::$ones[$num] : $tens;
        } elseif ($num > 0) {
            $parts[] = self::$ones[$num];
        }

        return implode(' ', $parts);
    }
}