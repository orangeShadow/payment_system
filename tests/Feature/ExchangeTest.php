<?php

namespace Tests\Feature;

use OrangeShadow\Payments\Core\Bank;
use Tests\TestCase;
use OrangeShadow\Payments\Core\Money;
use OrangeShadow\Payments\Contracts\RateableInterface;

class ExchangeTest extends TestCase
{

    public $rate = [
        'RUB' => 65.45,
        'EUR' => 0.87
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $dt = new \DateTime();
        $mock = \Mockery::mock('OrangeShadow\Payments\Contracts\RateableInterface')
            ->shouldReceive('getRate')
            ->with('RUB', $dt)
            ->andReturn($this->rate['RUB'])
            ->shouldReceive('getRate')
            ->with('EUR', $dt)
            ->andReturn($this->rate['EUR'])
            ->getMock();

        $bank = new Bank($mock);

        $moneyEurUsd = $bank->exchange(new Money(100, 'EUR'), 'USD', $dt);
        $this->assertEquals('114.94', $moneyEurUsd->getAmount());

        $moneyUsdEur = $bank->exchange(new Money(100, 'USD'), 'EUR', $dt);
        $this->assertEquals('87.00', $moneyUsdEur->getAmount());


        $moneyRubUsd = $bank->exchange(new Money(6545, 'RUB'), 'USD', $dt);
        $this->assertEquals('100.00', $moneyRubUsd->getAmount());

        $moneyUsdRub = $bank->exchange(new Money(100, 'USD'), 'RUB', $dt);
        $this->assertEquals('6545.00', $moneyUsdRub->getAmount());

        $moneyEurRub = $bank->exchange(new Money(100, 'EUR'), 'RUB', $dt);
        $this->assertEquals('7522.82', $moneyEurRub->getAmount());

        $moneyRubEur = $bank->exchange(new Money('7522.82', 'RUB'), 'EUR', $dt);
        $this->assertEquals('100.00', $moneyRubEur->getAmount());

        $moneyEurUsd = $bank->exchange(new Money(100, 'EUR'), 'USD', $dt);
        $moneyUsdRub = $bank->exchange(new Money($moneyEurUsd->getAmount(), 'USD'), 'RUB', $dt);
        $this->assertEquals($moneyEurRub->getAmount(), $moneyUsdRub->getAmount());

    }
}
