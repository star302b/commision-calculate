<?php

namespace CommisionCalculate\Tests\Processor;

use CommisionCalculate\ApiClient\interfaces\ApiClientInterface;
use CommisionCalculate\DataParser\interfaces\DataParserInterface;
use CommisionCalculate\Processor\TransactionProcessor;
use CommisionCalculate\Service\EuCountryService;
use Exception;
use PHPUnit\Framework\TestCase;

class TransactionProcessorTest extends TestCase
{
    public function testProcess()
    {
        $dataParser = $this->createMock(DataParserInterface::class);
        $binApiClient = $this->createMock(ApiClientInterface::class);
        $exchangeApiClient = $this->createMock(ApiClientInterface::class);
        $euCountryService = $this->createMock(EuCountryService::class);

        $dataParser->method('parseTransactionData')
            ->willReturnMap([
                ['{"bin":"45717360","amount":"100.00","currency":"EUR"}', ['bin' => '45717360', 'amount' => '100.00', 'currency' => 'EUR']]
            ]);

        $binApiClient->method('getData')
            ->willReturnMap([
                ['45717360', ['country' => ['alpha2' => 'AT']]]
            ]);

        $euCountryService->method('isEuCountry')
            ->willReturnMap([
                ['AT', true],
            ]);

        $exchangeApiClient->method('getData')
            ->willReturnMap([
                ['EUR', 1],
            ]);

        $processor = new TransactionProcessor($dataParser, $binApiClient, $exchangeApiClient, $euCountryService);
        
        $expectedOutput = "1\n";
        $output = $this->getFunctionOutput([$processor, 'process'], ['input.txt']);
        
        

        $this->assertEquals($expectedOutput, $output);
    }

    private function getFunctionOutput(callable $function, array $args = [])
    {
        ob_start();
        $function(...$args);
        return ob_get_clean();
    }
}
