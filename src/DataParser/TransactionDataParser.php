<?php
namespace CommisionCalculate\DataParser;

use Exception;
use JsonException;
use CommisionCalculate\DataParser\interfaces\DataParserInterface;

class TransactionDataParser implements DataParserInterface {

    public function parseTransactionData( $row ) {

        try {
            $transactionData = json_decode( $row, true, 512, JSON_THROW_ON_ERROR );
        } catch ( JsonException $e ) {
            throw $e;
        }

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new Exception( 'Invalid JSON provided' );
        }

        if ( ! isset( $transactionData[ 'bin' ] ) || ! isset( $transactionData[ 'amount' ] ) || ! isset( $transactionData[ 'currency' ] ) ) {
            throw new Exception( 'Invalid transaction data' );
        }

        // Ensure data is in expected format or types
        if ( ! is_numeric( $transactionData[ 'bin' ] ) 
            || ! is_numeric( $transactionData[ 'amount' ] )
            || ! is_string( $transactionData[ 'currency' ] ) ) {
            throw new Exception( 'Invalid transaction data types' );
        }

        return [
            'bin'       => ( string ) $transactionData[ 'bin' ],
            'amount'    => ( float ) $transactionData[ 'amount' ],
            'currency'  => $transactionData[ 'currency' ],
        ];
        
    }
}