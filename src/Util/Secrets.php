<?php

namespace Paypack\Util;

class Secrets
{
    /** @var string The Paypack API secrets to be used for requests. */
    public static $client_secrets;

    /**
     * @return string the API key used for requests
     */
    public static function getClientSecrets()
    {
        return self::$client_secrets;
    }

    /**
     * Sets the Client secret to be used for requests.
     *
     * @param string $secret
     */
    public static function setClientSecrets($secrets)
    {
        self::$client_secrets = $secrets;
    }
}
