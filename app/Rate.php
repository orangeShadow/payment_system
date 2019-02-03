<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OrangeShadow\Payments\Contracts\RateableInterface;

class Rate extends Model implements RateableInterface
{
    protected $fillable = ['currency', 'rate', 'created_at'];
    public $timestamps = false;

    /**
     * @param string $currency
     * @param \DateTime $dateTime
     *
     * @return float
     * @throws ModelNotFoundException
     */
    public function getRate(string $currency, \DateTime $dateTime): float
    {
        $rate = static::where('created_at', $dateTime->format('Y-m-d'))
            ->where('currency', $currency)
            ->firstOrFail();

        return $rate->rate;
    }
}
