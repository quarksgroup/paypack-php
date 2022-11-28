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
        if (Token::getAccessToken() && Token::getRefreshToken()) {
            return true;
        }
        return false;
    }
}
