<?php


namespace Eshop\Models\Cart;


class CartChannel
{
    public const ESHOP     = 'eshop';
    public const PHONE     = 'phone';
    public const POS       = 'pos';
    public const FACEBOOK  = 'facebook';
    public const INSTAGRAM = 'instagram';
    public const SKROUTZ   = 'skroutz';
    public const OTHER     = 'other';

    public static function all(): array
    {
        return [self::ESHOP, self::PHONE, self::POS, self::FACEBOOK, self::INSTAGRAM, self::SKROUTZ, self::OTHER];
    }
}
