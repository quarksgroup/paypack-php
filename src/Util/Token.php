<?php

namespace Paypack\Util;

class Token
{

    /** @var string The Paypack API tokens to be used for requests. */
    public static $access;
    public static $refresh;

    /**
     * @return null|string The Paypack auth access token for connected account
     *   requests
     */
    public static function getAccessToken()
    {
        if (isset($_SESSION['paypack_access_token'])) {
            return $_SESSION['paypack_access_token'];
        }
        return self::$access;
    }

    /**
     * Sets the access token to be used for Connect requests.
     *
     * @param string $clientId
     */
    public static function setAccessToken($token)
    {
        self::$access = $token;

        if (isset($_SESSION)) {
            $_SESSION['paypack_access_token'] = $token;
        }
    }

    /**
     * @return null|string The Paypack auth refresh token for connected accounts
     *   requests
     */
    public static function getRefreshToken()
    {
        if (isset($_SESSION['paypack_refresh_token'])) {
            return $_SESSION['paypack_refresh_token'];
        }
        return self::$refresh;
    }

    /**
     * Sets the refresh token to be used for Connect requests.
     *
     * @param string $clientId
     */
    public static function setRefreshToken($token)
    {
        self::$refresh = $token;
        if (isset($_SESSION)) {
            $_SESSION['paypack_refresh_token'] = $token;
        }
    }
}
