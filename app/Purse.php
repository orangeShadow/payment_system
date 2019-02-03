<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OrangeShadow\Payments\Contracts\MoneyInterface;
use OrangeShadow\Payments\Core\Money;
use OrangeShadow\Payments\Exceptions\PurseChangeAmountException;
use OrangeShadow\Payments\Exceptions\PurseChangeCurrencyException;

class Purse extends Model implements MoneyInterface
{

    protected $fillable = ['user_id', 'amount', 'currency'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * closed for update
     * @param string $currency
     * @throws PurseChangeCurrencyException
     */
    public function setCurrencyAttribute(string $currency)
    {
        if (isset($this->original['currency'])) {
            throw new PurseChangeCurrencyException();
        }
        $this->attributes['currency'] = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency():string
    {
        return $this->attributes['currency'];
    }

    /**
     * @return string
     */
    public function getAmount():string
    {
        return $this->attributes['amount'];
    }

    /**
     * @param string $amount
     *
     * @throws PurseChangeAmountException
     */
    public function setAmountAttribute($amount): void
    {
        if (isset($this->original['currency'])) {
            throw new PurseChangeAmountException();
        }
        $this->attributes['amount'] = $amount;
    }

    /**
     * @param Money $addedMoney
     *
     * @return Purse
     * @throws \OrangeShadow\Payments\Exceptions\NotEqualCurrenciesException
     */
    public function addBalance(MoneyInterface $addedMoney): MoneyInterface
    {
        $money = new Money($this->attributes['amount'], $this->attributes['currency']);
        $this->attributes['amount'] = $money->addBalance($addedMoney)->getAmount();

        return $this;
    }

    /**
     * @param Money $addedMoney
     *
     * @return Purse
     * @throws \OrangeShadow\Payments\Exceptions\NotEqualCurrenciesException
     */
    public function subBalance(MoneyInterface $addedMoney): MoneyInterface
    {
        $money = new Money($this->attributes['amount'], $this->attributes['currency']);
        $this->attributes['amount'] = $money->subBalance($addedMoney)->getAmount();

        return $this;
    }
}
