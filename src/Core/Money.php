<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Core;


use OrangeShadow\Payments\Contracts\MoneyInterface;
use OrangeShadow\Payments\Exceptions\NotEqualCurrenciesException;
use OrangeShadow\Payments\Traits\ArithmeticOperationsTraits;

class Money implements MoneyInterface
{
    /**
     * @var string
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    use ArithmeticOperationsTraits;

    /**
     * Money constructor.
     * @param string $amount get amount with decimal
     * @param string $currency
     */
    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }


    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param MoneyInterface $money
     * @return MoneyInterface
     *
     * @throws NotEqualCurrenciesException
     */
    public function addBalance(MoneyInterface $money): MoneyInterface
    {
        if ($money->getCurrency() !== $this->getCurrency()) {
            throw new NotEqualCurrenciesException();
        }

        $this->amount = bcadd($this->getAmount(), $money->getAmount(),2);

        return $this;
    }

    /**
     * @param MoneyInterface $money
     * @return MoneyInterface
     * @throws NotEqualCurrenciesException
     */
    public function subBalance(MoneyInterface $money): MoneyInterface
    {
        if ($money->getCurrency() !== $this->getCurrency()) {
            throw new NotEqualCurrenciesException();
        }

        $this->amount = bcsub($this->getAmount(), $money->getAmount(),2);

        return $this;
    }
}
