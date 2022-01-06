<?php

function Transaction($transactionId)
{
    if (!isset($transactionId)) return ["message" => "Transaction Ref is required"];
    try {
        $response = HttpClient::getClient()->get('transactions/find/' . $transactionId);
        return json_decode($response->getBody(), true);
    } catch (ClientException $e) {
        return Psr7\Message::toString($e->getResponse());
    } catch (RequestException $e) {
        return Psr7\Message::toString($e->getResponse());
    }
}
