<?php
namespace CommisionCalculate\ApiClient;

use Exception;
use CommisionCalculate\ApiClient\interfaces\ApiClientInterface;

class ExchangeRatesApiClient implements ApiClientInterface {

    private const BASE_URL = 'http://api.exchangeratesapi.io/latest?access_key=836820728cbdc1dccebc4a3deb90cbb5';

    public function getData( $currency ) {

        $exchangeRates = file_get_contents( self::BASE_URL );

        if (!$exchangeRates)
            throw new Exception( 'Error fetching exchange rates!' );

        $rates = json_decode( $exchangeRates, true )[ 'rates' ];
        

        return isset( $rates[ $currency ] ) ? $rates[ $currency ] : 0;

    }
}