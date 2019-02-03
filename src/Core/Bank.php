<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Core;

use \DateTime;
use OrangeShadow\Payments\Contracts\BankInterface;
use OrangeShadow\Payments\Contracts\MoneyInterface;
use OrangeShadow\Payments\Contracts\RateableInterface;
use OrangeShadow\Payments\Exceptions\NotEqualCurrenciesException;

class Bank implements BankInterface
{
    /**
     * @var RateableInterface
     */
    protected $rate;

    /**
     * Bank constructor.
     *
     * @param RateableInterface $rate
     */
    public function __construct(RateableInterface $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @param MoneyInterface $moneyOne
     * @param MoneyInterface $moneyTwo
     * @return int
     * @throws NotEqualCurrenciesException
     */
    public function compare(MoneyInterface $moneyOne, MoneyInterface $moneyTwo): int
    {
        if ($moneyTwo->getCurrency() !== $moneyOne->getCurrency()) {
            throw new NotEqualCurrenciesException();
        }

        return bccomp($moneyOne->getAmount(), $moneyTwo->getAmount(), 2);
    }

    /**
     * @param string $currency
     * @param \DateTime $dateTime
     * @return float
     */
    public function getRate(string $currency, DateTime $dateTime): float
    {
        if ($currency === 'USD') {
            return 1.00;
        }

        return $this->rate->getRate($currency, $dateTime);
    }

    /**
     * @param MoneyInterface $moneyFrom
     * @param string $currencyTo
     * @param DateTime|null $exchangeDate
     *
     * @return MoneyInterface
     */
    public function exchange(MoneyInterface $moneyFrom, string $currencyTo, ?DateTime $exchangeDate = null): MoneyInterface
    {
        if (is_null($exchangeDate)) {
            $exchangeDate = new DateTime();
        }

        if ($moneyFrom->getCurrency() === $currencyTo) {
            return $moneyFrom;
        }

        if ($currencyTo === 'USD' || $moneyFrom->getCurrency() === 'USD') {
            $rateFrom = $this->getRate($moneyFrom->getCurrency(), $exchangeDate);
            $rateTo = $this->getRate($currencyTo, $exchangeDate);

            $db = \DB::select(\DB::raw("SELECT CONVERT($rateTo/$rateFrom*{$moneyFrom->getAmount()},DECIMAL(19,2)) as v"));
            $amount = $this->round($db[0]->v);

//            $scale = bcdiv((string)$rateTo, (string)$rateFrom, 2);
//            $amount = bcmul($moneyFrom->getAmount(), $scale, 2);

            return new Money($amount, $currencyTo);
        }

        $amountUsd = $this->exchange($moneyFrom, 'USD', $exchangeDate);

        return $this->exchange($amountUsd, $currencyTo, $exchangeDate);

    }

    /**
     * string $scale
     */
    protected function round(string $number): string
    {
        $dot = strpos($number, '.');
        $last = (int)substr($number, $dot + 3, 1);
        $preLast = (int)substr($number, $dot + 2, 1);

        if ($last > 5) {
            $preLast++;
        }

        if ($last === 5 && $preLast % 2 === 1) {
            $preLast++;
        }

        $number[ $dot + 2 ] = $preLast;
        $number = substr($number, 0, $dot + 2 + 1);

        return $number;

    }
}
