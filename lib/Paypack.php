<?php

namespace Paypack;

require_once '/vendor/autoload.php';

require_once __DIR__ . '/util/util.php';
require_once __DIR__ . '/HttpClient/httpClient.php';

require_once __DIR__ . '/methods/Me.php';
require_once __DIR__ . '/methods/Events.php';
require_once __DIR__ . '/methods/Cashin.php';
require_once __DIR__ . '/methods/Cashout.php';
require_once __DIR__ . '/methods/Transaction.php';
require_once __DIR__ . '/methods/Transactions.php';


use HttpClient;
use Secrets;

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
     *
     */
    public static function config(array $secrets)
    {
        if (!isset($secrets)) {
            throw ["message" => "Client secrets are required."];
        }

        Secrets::setClientSecrets($secrets);
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
