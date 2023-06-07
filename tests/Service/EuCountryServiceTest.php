<?php

namespace CommisionCalculate\Tests;

use CommisionCalculate\Service\EuCountryService;
use PHPUnit\Framework\TestCase;

class EuCountryServiceTest extends TestCase {
    public function testIsEuCountry() {
        $service = new EuCountryService();

        $this->assertTrue($service->isEuCountry('AT'));
        $this->assertFalse($service->isEuCountry('US'));
    }
}
