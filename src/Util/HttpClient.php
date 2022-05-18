<?php

namespace Paypack\Util;

use Paypack\Util\Auth;
use Paypack\Util\Token;
use Paypack\Util\Secrets;

use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;


class HttpClient
{
    /** @var string Http client for the Paypack API. */
    public static $httpClient = null;

    /** @var string The base URL for the Paypack API. */
    public static $apiBaseURL = 'https://payments.paypack.rw/api/';

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
                RequestException $exception = null
            ) {
                $maxRetries = 3;

                if ($retries >= $maxRetries) {
                    return false;
                }

                if ("/api/auth/agents/authorize" !== $request->getUri()->getPath() && $response && $response->getStatusCode() === 401) {
                    echo "Refreshing Expired Tokens\n";

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

            return $request->withAddedHeader("Authorization", Token::getAccessToken())->withAddedHeader("X-Webhook-Mode", '127.0.0.1' == $_SERVER["REMOTE_ADDR"] ? 'development' : 'production');
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
            $response = HttpClient::getClient()->post('auth/agents/authorize', ["json" => Secrets::getClientSecrets()]);

            $body = json_decode($response->getBody(), true);

            if (200 === $response->getStatusCode()) {
                Token::setAccessToken($body['access']);
                Token::setRefreshToken($body['refresh']);
            } else {
                return $body;
            }
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        } catch (RequestException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }

    public static function refreshClientAccessToken()
    {
        if (!Token::getRefreshToken()) return false;
        try {
            $response = self::getClient()->get('auth/refresh/' . Token::getRefreshToken());
            $body = json_decode($response->getBody(), true);

            if (200 === $response->getStatusCode()) {
                Token::setAccessToken($body['access']);
                Token::setRefreshToken($body['refresh']);
            }

            return true;
        } catch (ClientException $e) {
            print_r(Psr7\Message::toString($e->getResponse()));
            return true;
        } catch (RequestException $e) {
            print_r(Psr7\Message::toString($e->getResponse()));
            return true;
        }
    }
}
