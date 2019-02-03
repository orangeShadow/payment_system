<?php
declare(strict_types=1);

namespace OrangeShadow\Payments\Exceptions;

use Throwable;

class PurseChangeCurrencyException extends PaymentsException
{
    public function __construct(string $message = "You can't change currency of purse", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
