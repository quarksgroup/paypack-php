<?php

namespace Paypack\Util;

use Paypack\Util\Auth;
use Paypack\Util\Token;
use Paypack\Util\Secrets;

use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;


class HttpClient
{
    /** @var object Http client for the Paypack API. */
    public static $httpClient = null;

    /** @var string The base URL for the Paypack API. */
    public static $apiBaseURL = 'https://payments.paypack.rw/api/';

    /** @var string The webhook mode used for requests. */
    public static $webhookMode = 'development';

    /** @var array The header used for requests. */
    public static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    /**
     * @return void sets the API Http Client used for requests
     */
    public static function init()
    {
        $baseuri = self::$apiBaseURL;

        $stack = new HandlerStack();
        $stack->setHandler(Utils::chooseHandler());

        $stack->push(Middleware::retry(
            function (
                $retries,
                Request $request,
                Response $response = null,
            ) {
                $maxRetries = 3;

                if ($retries >= $maxRetries) {
                    return false;
                }

                if ("/api/auth/agents/authorize" !== $request->getUri()->getPath() && $response && $response->getStatusCode() === 401) {
                    return self::refreshClientAccessToken();
                }

                return false;
            }
        ));

        //Add Authorization header with token
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            if (!Auth::isAuthenticated())
                if ("/api/auth/agents/authorize" !== $request->getUri()->getPath())
                    self::authorize();

            $request = $request->withHeader('Authorization', 'Bearer ' . Token::getAccessToken());
            $request = $request->withHeader('X-Webhook-Mode', self::$webhookMode);

            foreach (self::$headers as $key => $value) {
                $request = $request->withHeader($key, $value);
            }

            return $request;
        }));

        self::$httpClient = new Client([
            'base_uri' => $baseuri,
            'handler' => $stack
        ]);
    }

    public static function getClient()
    {
        return (object) self::$httpClient;
    }

    /**
     * Sets the refresh token to be used for Connect requests.
     */
    public static function authorize()
    {
        try {
            $response = self::$httpClient->post('auth/agents/authorize', [
                'json' => Secrets::getClientSecrets()
            ]);

            $response = json_decode($response->getBody()->getContents());

            Token::setAccessToken($response->access);
            Token::setRefreshToken($response->refresh);

            return true;
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new \Exception($response->message);
        } catch (RequestException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function setMode($mode)
    {
        self::$webhookMode = $mode;
    }

    public static function setHeaders($headers)
    {
        self::$headers = $headers;
    }

    public static function refreshClientAccessToken()
    {
        try {
            if (Token::getRefreshToken() == null) {
                $response = self::$httpClient->post('auth/agents/authorize', [
                    'json' => Secrets::getClientSecrets()
                ]);
            } else {
                $response = self::$httpClient->post('auth/refresh/' . Token::getRefreshToken());
            }

            $response = json_decode($response->getBody()->getContents());

            Token::setAccessToken($response->access);
            Token::setRefreshToken($response->refresh);

            return true;
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new \Exception($response->message);
        } catch (RequestException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
