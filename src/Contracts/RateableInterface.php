<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Contracts;


interface RateableInterface
{
    /**
     * @param string $currency
     * @param \DateTime $dateTime
     * @return float
     */
    public function getRate(string $currency, \DateTime $dateTime):float;
}
