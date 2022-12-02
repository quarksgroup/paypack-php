<?php

namespace Paypack;

use Paypack\Util\Secrets;
use Paypack\Util\HttpClient;
use Paypack\Util\Token;

require_once __DIR__ . '/Methods/Me.php';
require_once __DIR__ . '/Methods/Events.php';
require_once __DIR__ . '/Methods/Cashin.php';
require_once __DIR__ . '/Methods/Cashout.php';
require_once __DIR__ . '/Methods/Transaction.php';
require_once __DIR__ . '/Methods/Transactions.php';


/**
 * Class Paypack.
 */
class Paypack
{

    public function __construct()
    {
        HttpClient::init();
    }

    /**
     * Sets the refresh token to be used for Connect requests.
     *
     * @property string $client_id
     * @property string $client_secret
     * @property string $refresh_token
     * @property string $access_token
     * @property string $X-Webhook-Mode
     * @property array $headers
     *
     */
    public static function config(array $configs)
    {
        if (!isset($configs['client_id']) || !isset($configs['client_secret'])) {
            throw new \Exception('Client ID and Client Secret are required');
        }

        Secrets::setClientSecrets([
            'client_id' => $configs['client_id'],
            'client_secret' => $configs['client_secret'],
        ]);

        if (isset($configs['access_token'])) {
            Token::setAccessToken($configs['access_token']);
        }

        if (isset($configs['refresh_token'])) {
            Token::setRefreshToken($configs['refresh_token']);
        }

        if (isset($configs['webhook_mode'])) {
            HttpClient::setMode($configs['webhook_mode']);
        }

        if (isset($configs['headers'])) {
            HttpClient::setHeaders($configs['headers']);
        }
    }

    /**
     * Fetch transactions according to filter parameters
     * 
     * @property string $limit limit of transactions to fetch default is 20
     * @property string $offset offset of transactions to fetch
     * @property string $from starting date range of transactions to fetch
     * @property string $to ending date range of transactions to fetch
     * @property string $kind kind of transactions to fetch eg: CASHIN or CASHOUT
     * @property int $client transactions for a specific client
     */
    public static function Transactions($filters = null)
    {
        return Transactions($filters);
    }

    /**
     * Fetch transaction according to the transaction ref
     * 
     * @param string $transactionId transaction ref
     */
    public static function Transaction($transactionId = null)
    {
        return Transaction($transactionId);
    }

    /**
     * Initiates a cashin request.
     *
     * @property string $phone
     * @property int $amount
     */
    public static function Cashin($param)
    {
        return Cashin($param);
    }

    /**
     * Initiates a cashout request.
     *
     * @property string $phone
     * @property int $amount
     */
    public static function Cashout($param)
    {
        return Cashout($param);
    }

    /**
     * Fetch events according to filter parameters.
     *
     * @property string $limit limit of events to fetch default is 20
     * @property string $offset offset of events to fetch
     * @property string $from starting date range of events to fetch
     * @property string $to ending date range of events to fetch
     * @property string $kind kind of events to fetch eg: CASHIN or CASHOUT
     * @property int $client events for a specific client
     * @property string $ref events for a specific transaction ref
     * @property string $status events with a specific status eg: pending or successfull or failed
     */

    public static function Events($param = null)
    {
        return Events($param);
    }

    /**
     * Provides a profile of authenticated user
     * 
     */
    public static function Me()
    {
        return Me();
    }
}
