<?php

namespace Eshop\Services;

/**
 * A slug (pretty URL) generator, which supports Greek UTF-8 characters
 *
 */
class SlugGenerator
{
    /**
     * Generates a slug (pretty url) based on a string, which is typically a page/article title
     *
     * @param string $string
     * @param string $separator
     * @return string the generated slug
     */
    public static function getSlug(string $string, string $separator = '-'): string
    {
        $slug = '';
        $lastCharacter = '';
        $string = mb_strtolower(trim($string), 'utf-8');
        
        $iMax = mb_strlen($string, 'utf-8');
        for ($i = 0; $i < $iMax; $i++) {
            $tempCharacter = self::utf8_substr($string, $i, 1);
            $currentCharacter = self::convertCharacter($tempCharacter, $separator);

            if ($currentCharacter === '' || ($currentCharacter === $lastCharacter && $currentCharacter === $separator)) {
                continue;
            }

            $lastCharacter = $currentCharacter;
            $slug .= $currentCharacter;
        }

        return $slug;
    }

    /**
     * A UTF-8 substr function adapted from the following: http://us.php.net/manual/en/function.substr.php#44838
     *
     * @param string $str
     * @param int    $start
     * @param int    $end
     * @return string a utf-8 character
     */
    private static function utf8_substr(string $str, int $start, int $end): string
    {
        preg_match_all('/./su', $str, $ar);
        return implode('', array_slice($ar[0], $start, $end));
    }

    /**
     * Converts a character to a slug-friendly character.
     *
     * If it is a Greek character, converts it to an English equivalent.
     * If it is an English character or a number, returns the same character/number.
     * If it is a space, converts it to the selected separator.
     * If it is a symbol, either translates it to the selected separator (depending on the rules), or just ignores it and returns an empty string.
     *
     * @param string $character
     * @param string $separator
     * @return string the converted character
     */
    protected static function convertCharacter(string $character, string $separator): string
    {
        $allowedCharacters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', $separator];

        if (in_array($character, $allowedCharacters)) {
            return $character;
        }

        return match ($character) {
            ' ', => $separator,
            'α', 'ά' => 'a',
            'β' => 'v',
            'ç' => 'c',
            'γ', 'ğ' => 'g',
            'δ' => 'd',
            'ε', 'έ' => 'e',
            'ζ' => 'z',
            'θ' => 'th',
            'ι', 'ί', 'ϊ', 'ΐ', 'η', 'ή', 'ı' => 'i',
            'κ' => 'k',
            'λ' => 'l',
            'μ' => 'm',
            'ν' => 'n',
//            'η', 'ή' => 'h',
            'ξ' => 'ks',
            'ο', 'ό', 'ö' => 'o',
            'π' => 'p',
            'ρ' => 'r',
            'σ', 'ς' => 's',
            'ş' => 'sh',
            'τ' => 't',
            'ü', 'υ', 'ύ' => 'u',
            'ϋ', 'ΰ' => 'y',
            'φ' => 'f',
            'χ' => 'x',
            'ψ' => 'ps',
            'ω', 'ώ' => 'w',
            default => '',
        };
    }
}
