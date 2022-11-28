
# Paypack PHP

Paypack is a cloud service that offers a solution to merchants in need of a resilient, robust and efficient payment service. 

Easily request and send funds . Funds are seamlessly delivered to their recipients via mobile money.

Paypack-php is a wrapper around the Paypack REST API that can be easily integrated with any PHP framework.



## Setup

Get [Composer](https://getcomposer.org). For example, on Mac OS:

```bash
brew install composer
```

Install dependencies:

```bash
composer require quarksgroup/paypack-php
```


## Usage

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/quarksgroup/paypack-php). Then, to use the bindings, include the `init.php` file.

```php
require_once('/path/to/paypack-php/init.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer.
-   [`json`](https://secure.php.net/manual/en/book.json.php)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Integrating Paypack into your app begins with [creating a Paypack account](https://payments.paypack.rw/register). After a successful registration create an application and a set of `client_id` , `client_secrets` tokens will be generated. 

***NOTE:*** Make sure to copy before closing the modal as it's the last time the `client_secret` will be printed to the screen.


## Quickstart

Assuming you have your Paypack configuration parameters defined (`client_id`,`client_secret`), making any request is very simple.

Simple usage looks like:

```php
$paypack = new  Paypack();

$paypack->config([
'client_id' => 'xxxx',
'client_secret' => 'xxxx'
]);

$transactions = $paypack->Transactions();

print_r($transactions);
```

## Intergration

### Cashin Request

The following example generates a cashin request:

```php 
$cashin = $paypack->Cashin([
	'phone' => "078xxxxxxx",
	'amount' => "100"
]); 

print_r($cashin);
```

##

### Cashout Request

The following example generates a cashout request:

```php 
$cashout = $paypack->Cashout([
	'phone' => "078xxxxxxx",
	'amount' => "100"
]); 

print_r($cashout);
```

##

### Transactions

The following example returns a list of 100 latest transactions:

```php 
$transactions = $paypack->Transactions([
	'offset' => "0",
	'limit' => "100"
]);

print_r($transactions);
```

**Info :** This method supports a number of filters.

```php
 - offset	String() // offset of transactions to fetch
 - limit	String() // limit of transactions to fetch default is 20
 - from		Date()	// starting date range of transactions to fetch
 - to		Date() // ending date range of transactions to fetch
 - kind		String() //  kind of transactions to fetch eg: CASHIN or CASHOUT
 - client	Number() // transactions for a specific client
```

##


### Transaction

The following example returns one transaction according to its reference number:

```php
$transaction = $paypack->Transaction($transactionRef);

print_r($transaction);
```

##

### Events

The following example returns a list of 100 latest events:

```php 
$events= $paypack->Events([
	'offset' => "0",
	'limit' => "100"
]);

print_r($events);
```

**Info :** This method supports a number of filters.

```php
- offset	String() // offset of events to fetch
 - limit	String() // limit of events to fetch default is 20
 - from		Date()	// starting date range of events to fetch
 - to		Date() // ending date range of events to fetch
 - kind		String() //  kind of events to fetch eg: CASHIN or CASHOUT
 - client	Number() // events for a specific client
 - ref		String() // events for a specific transaction ref
 - status	String() // events with a specific status eg: pending or successfull or failed
```

##

### Profile

The following example returns the profile of the authenticated merchant:

```php 
$profile= $paypack->Me(); 

print_r($profile);
```

## Support

If you meet challenges during your intergration, you can [open an issue through GitHub](https://github.com/quarksgroup/paypack-js/issues).

## License

Released under the MIT license.