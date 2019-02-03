<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Contracts;

use OrangeShadow\Payments\Exceptions\NotEqualCurrenciesException;

interface MoneyInterface
{

    /**
     * @return string
     */
    public function getAmount(): string;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @param MoneyInterface $money
     * @return MoneyInterface
     *
     * @throws NotEqualCurrenciesException
     */
    public function addBalance(MoneyInterface $money): MoneyInterface;

    /**
     * @param MoneyInterface $money
     * @return MoneyInterface
     *
     * @throws NotEqualCurrenciesException
     */
    public function subBalance(MoneyInterface $money): MoneyInterface;
}
