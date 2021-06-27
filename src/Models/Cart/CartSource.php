<?php


namespace Eshop\Models\Cart;


class CartSource
{
    public const ESHOP     = 'eshop';
    public const PHONE     = 'phone';
    public const POS       = 'pos';
    public const FACEBOOK  = 'facebook';
    public const INSTAGRAM = 'instagram';
    public const OTHER     = 'other';

    public static function all(): array
    {
        return [self::ESHOP, self::PHONE, self::POS, self::FACEBOOK, self::INSTAGRAM, self::OTHER];
    }
}
