<?php

function Me()
{
    try {
        $response = HttpClient::getClient()->get('merchants/me');
        return json_decode($response->getBody(), true);
    } catch (ClientException $e) {
        return Psr7\Message::toString($e->getResponse());
    } catch (RequestException $e) {
        return Psr7\Message::toString($e->getResponse());
    }
}
