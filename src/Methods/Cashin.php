<?php

use Paypack\Util\HttpClient;

function Cashin(array $param)
{
    $client = HttpClient::getClient();

    if (!isset($param['phone'])) {
        throw new \Exception("Property 'phone' is required to cashin");
    }

    if (!isset($param['amount'])) {
        throw new \Exception("Property 'amount' is required to cashin");
    }

    $amount = (int) $param['amount'];
    $phone = $param['phone'];

    if (!is_numeric($amount)) {
        throw new \Exception("Property 'amount' must be a number to cashin");
    }

    if ($param['amount'] < 100) {
        throw new \Exception("Minimum amount to cashin is 100");
    }

    try {
        $response = $client->post('transactions/cashin', [
            'json' => [
                'number' => $phone,
                'amount' => $amount,
            ],
        ]);

        return json_decode($response->getBody(), true);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        $responseBody = json_decode($responseBodyAsString, true);

        throw new \Exception($responseBody['message']);
    } catch (\GuzzleHttp\Exception\ServerException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        $responseBody = json_decode($responseBodyAsString, true);

        throw new \Exception($responseBody['message']);
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
        throw new \Exception('Failed to connect to Paypack API');
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        throw new \Exception('Request failed to complete');
    } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
    }
}
