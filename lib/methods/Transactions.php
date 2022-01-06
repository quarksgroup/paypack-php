<?php

function Transactions($filters = null)
{
    try {
        $response = HttpClient::getClient()->get('transactions/list?', ["query" => $filters]);
        return json_decode($response->getBody(), true);
    } catch (ClientException $e) {
        return Psr7\Message::toString($e->getResponse());
    } catch (RequestException $e) {
        return Psr7\Message::toString($e->getResponse());
    }
}
