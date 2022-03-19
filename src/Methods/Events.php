<?php

use GuzzleHttp\Psr7\Message;
use Paypack\Util\HttpClient;

function Events($filters = null)
{
    try {
        $response = HttpClient::getClient()->get('events/transactions?', ["query" => $filters]);
        return json_decode($response->getBody(), true);
    } catch (ClientException $e) {
        return Psr7\Message::toString($e->getResponse());
    } catch (RequestException $e) {
        return Psr7\Message::toString($e->getResponse());
    }
}
