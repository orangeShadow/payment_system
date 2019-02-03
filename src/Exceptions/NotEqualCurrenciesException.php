<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Exceptions;

use Throwable;

class NotEqualCurrenciesException extends PaymentsException
{
    public function __construct(string $message = "Currencies is not equal ", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
