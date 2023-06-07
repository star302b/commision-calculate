<?php
require_once( 'vendor/autoload.php' );

use CommisionCalculate\ApiClient\BinListApiClient;
use CommisionCalculate\ApiClient\ExchangeRatesApiClient;
use CommisionCalculate\DataParser\TransactionDataParser;
use CommisionCalculate\Processor\TransactionProcessor;
use CommisionCalculate\Service\EuCountryService;

$dataParser = new TransactionDataParser();
$binApiClient = new BinListApiClient();
$exchangeApiClient = new ExchangeRatesApiClient();
$euCountryService = new EuCountryService();

$transactionProcessor = new TransactionProcessor( $dataParser, $binApiClient, $exchangeApiClient, $euCountryService );
$transactionProcessor->process( $argv[1] );