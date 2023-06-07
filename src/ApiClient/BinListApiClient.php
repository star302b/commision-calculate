<?php
namespace CommisionCalculate\ApiClient;

use Exception;
use CommisionCalculate\ApiClient\interfaces\ApiClientInterface;

class BinListApiClient implements ApiClientInterface {

    private const BASE_URL = 'https://lookup.binlist.net/';

    public function getData( $bin ) {

        $url = self::BASE_URL . $bin;
        $binResults = file_get_contents( $url );

        if ( ! $binResults )
            throw new Exception( 'Error fetching BIN results!' );

        return json_decode( $binResults, true );
        
    }
}