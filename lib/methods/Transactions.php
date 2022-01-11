<?php

function Transactions($filters = null)
{
    if (null != $filters) {
        if (null == $filters['offset']) $filters['offset'] = "0";
    }

    try {
        $response = HttpClient::getClient()->get('transactions/list?', ["query" => $filters]);
        return json_decode($response->getBody(), true);
    } catch (ClientException $e) {
        return Psr7\Message::toString($e->getResponse());
    } catch (RequestException $e) {
        return Psr7\Message::toString($e->getResponse());
    }
}
