<?php

namespace CommisionCalculate\Tests;

use CommisionCalculate\DataParser\TransactionDataParser;
use PHPUnit\Framework\TestCase;

class TransactionDataParserTest extends TestCase
{
    public function testParseTransactionData()
    {
        $dataParser = new TransactionDataParser();
        $inputData = '{"bin":"45717360","amount":"100.00","currency":"EUR"}';
        $expectedResult = ['bin' => '45717360', 'amount' => '100.00', 'currency' => 'EUR'];

        $result = $dataParser->parseTransactionData($inputData);

        $this->assertEquals($expectedResult, $result);
    }

    public function testParseTransactionDataWithInvalidInput()
    {
        $dataParser = new TransactionDataParser();
        $inputData = 'invalid JSON';

        $this->expectException(\JsonException::class);

        $dataParser->parseTransactionData($inputData);
    }
}
