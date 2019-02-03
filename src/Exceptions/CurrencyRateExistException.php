<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Exceptions;


use Throwable;

class CurrencyRateExistException extends PaymentsException
{
    public function __construct(string $date,
                                int $code = 0, Throwable $previous = null)
    {
        $message = "Currency rate on date $date already exist.";
        parent::__construct($message, $code, $previous);
    }
}
