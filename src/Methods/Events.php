<?php

use Paypack\Util\HttpClient;

function Events($filters = null)
{
    $client = HttpClient::getClient();

    try {
        $response = $client->get('events/transactions?', [
            'query' => $filters,
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
