<?php
namespace CommisionCalculate\Processor;

use Exception;
use CommisionCalculate\ApiClient\interfaces\ApiClientInterface;
use CommisionCalculate\DataParser\interfaces\DataParserInterface;
use CommisionCalculate\Processor\interfaces\ProcessorInterface;
use CommisionCalculate\Service\EuCountryService;

class TransactionProcessor implements ProcessorInterface {
    private $dataParser;
    private $binApiClient;
    private $exchangeApiClient;
    private $euCountryService;

    public function __construct( DataParserInterface $dataParser, ApiClientInterface $binApiClient, ApiClientInterface $exchangeApiClient, EuCountryService $euCountryService ) {
        $this->dataParser = $dataParser;
        $this->binApiClient = $binApiClient;
        $this->exchangeApiClient = $exchangeApiClient;
        $this->euCountryService = $euCountryService;
    }

    public function process( $fileName ) {
        foreach ( explode( "\n", file_get_contents( $fileName ) ) as $row) {

            if ( empty( $row ) ) break;

            $transactionData = $this->dataParser->parseTransactionData( $row );

            if ( ! is_array( $transactionData ) ) break;

            $binResults = $this->binApiClient->getData( $transactionData[ 'bin' ] );

            if ( ! $binResults)
                throw new Exception( 'Error fetching BIN results!' );

            $isEu = $this->euCountryService->isEuCountry( $binResults[ 'country' ][ 'alpha2' ] );

            $rate = $this->exchangeApiClient->getData( $transactionData[ 'currency' ] );
            if ( $transactionData[ 'currency' ] == 'EUR' or $rate == 0) {
                $amntFixed = $transactionData[ 'amount' ];
            }
            if ( $transactionData[ 'currency' ] != 'EUR' or $rate > 0 ) {
                if ($rate == 0) {
                    throw new Exception( 'The exchange rate cannot be zero.' );
                } else {                
                    $amntFixed = $transactionData[ 'amount' ] / $rate;
                }
            }

            $commission = ceil( $amntFixed * ( $isEu ? 0.01 : 0.02 ) * 100 ) / 100;
            echo $commission;
            print "\n";
        }
    }

}
