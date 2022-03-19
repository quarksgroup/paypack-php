<?php

namespace Paypack\Util;

use Paypack\Util\Token;

class Auth
{
    /**
     * Checks if client is authenticated.
     *
     * @param string $secret
     */
    public static function isAuthenticated()
    {
        return null !== Token::getAccessToken();
    }
}
