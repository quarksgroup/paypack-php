<?php

function Cashin($param)
{
    if (!isset($param['phone']))
        return ["message" => "property 'phone' is required"];

    if (!isset($param['amount'])) return ["message" => "property 'amount' is required"];

    $amount = (int) $param['amount'];
    $phone = $param['phone'];

    if (!is_numeric($amount)) return ["message" => "Invalid amount"];

    if (100 > $amount) return ["message" => "Minimum amount to cashin is 100 Rwf"];

    try {
        $response = HttpClient::getClient()->post('transactions/cashin', ["json" => ['amount' => $amount, 'number' => $phone]]);
        return json_decode($response->getBody(), true);
    } catch (ClientException $e) {
        return Psr7\Message::toString($e->getResponse());
    } catch (RequestException $e) {
        return Psr7\Message::toString($e->getResponse());
    }
}
