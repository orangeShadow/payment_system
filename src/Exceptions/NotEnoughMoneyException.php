<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Exceptions;


use Throwable;

class NotEnoughMoneyException extends PaymentsException
{
    public function __construct(string $message = "User have not enough money",
                                int $code = 0,
                                Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
