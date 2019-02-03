<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Contracts;

use DateTime;

interface BankInterface
{
    /**
     * @param MoneyInterface $moneyOne
     * @param MoneyInterface $moneyTwo
     * @return int
     * @throws NotEqualCurrenciesException
     */
    public function compare(MoneyInterface $moneyOne, MoneyInterface $moneyTwo): int;


    /**
     * @param MoneyInterface $moneyFrom
     * @param string $currencyTo
     * @param null|DateTime $exchangeDate
     * @return MoneyInterface
     */
    public function exchange(MoneyInterface $moneyFrom,
                             string $currencyTo,
                             ?DateTime $exchangeDate = null): MoneyInterface;
}
